    const UpImg_obj = {

      divActiveImage : '',
      inputAlt : '',

      /**
       * [sendFile description]
       * @param  {[type]} inputFile [description]
       * @return {[type]}           [description]
       */
      sendFile(inputFile){

        let nameModel=inputFile.id;
        let postData = document.getElementById('post-data');
        let stock = document.getElementById('data-'+nameModel);



// console.log(stock);
        let data = JSON.stringify({
          table:
          {
            name_model: nameModel,
            // name_img: image.url.split('/').pop(),
            user_id: postData.dataset.userId,
            post_id: postData.dataset.postId,
          },
          property:{
            widthImg:  stock.dataset.widthImg,
            heightImg: stock.dataset.heightImg,

           },
        });

        UpImg_obj.sendAjax(
          'POST', '/add-image',
          UpImg_obj.createImageData(inputFile,data),
          function(msg){
// console.log(msg);
            const data = JSON.parse(msg);
            if(!data.error) {

              let divImg = document.getElementById('js-file-list-'+inputFile.id);

              divImg.insertAdjacentHTML( 'beforeend', data.image );

              inputFile.value = '';

            } else { alert(data.error); }
        });
      },
      /**
       * [editAlt description]
       * @param  {[type]} id [description]
       * @return {[type]}    [description]
       */
      editAlt(id){

        divActiveImage = document.getElementById(id);
        inputAlt = divActiveImage.querySelector('.alt');
        let modal = document.getElementById('alt-text');
        let modalDialog = modal.querySelector('.modal-dialog');
        let inputModal = modal.querySelector('input');
        inputModal.value = inputAlt.value;
        let rect = divActiveImage.getBoundingClientRect();
        modal.style.display='block';
        inputModal.focus();
        modalDialog.style.top = rect.top-300+"px";
        divActiveImage.style.border = "#FF0000FF 7px solid";
      },
      /*

      */
      saveModalAlt(){

        inputAlt = divActiveImage.querySelector('.alt');
        let modal = document.getElementById('alt-text');
        let inputModal = modal.querySelector('input');

        if(inputModal.value.length>0){
          inputAlt.value = inputModal.value;
          UpImg_obj.sendAlt(inputModal);
          inputModal  = '';
        }
        UpImg_obj.closeModalAlt();
      },
      /*

      */
      sendAlt(alt){

        const formData = new FormData();
        formData.append('id', divActiveImage.dataset.id);
        formData.append('alt', alt.value);

        UpImg_obj.sendAjax('POST', '/add-image-alt', formData, function(msg){

          const data = JSON.parse(msg);
        });
      },
      /*

      */
      closeModalAlt(){

        let modal = document.getElementById('alt-text');

        modal.style.display='none';

        divActiveImage.style.border = "none";
      },
      /*
      Удаление загруженной картинки
      */
      removeImg(id){

        divActiveImage = document.getElementById(id);
        id_db = divActiveImage.dataset.id;

        const formData = new FormData();

        formData.append('id', id_db);

        UpImg_obj.sendAjax('POST', '/del-image', formData, function(msg){
// console.log(msg);
          const data = JSON.parse(msg);

          if(!data.error) {
            document.getElementById(id).remove();
          }

        });
      },
      /**
       * [createImageData description]
       * @param  {[type]} inputFile [description]
       * @return {[type]}           [description]
       */
      createImageData(inputFile, data){

        const file = inputFile.files[0]; // получаем выбранный файл

        const formData = new FormData(); // создаем объект FormData для передачи файла

        formData.append('image', file); // добавляем файл в объект FormData
        formData.append('data', data); // добавляем данные в объект FormData

        return formData;
      },
      /*
      отправка ajax запросов на сервер
       */
      async sendAjax(type, url, data, callback){

        let token="";

        if (document.querySelector('meta[name="csrf-token"]')){

          token = document.querySelector('meta[name="csrf-token"]').content
        }



        if (window.XMLHttpRequest)
        {// код для IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        }
        else
        {// код для IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange=function()
        {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
          {
            callback(xmlhttp.responseText);
          }
        }

        xmlhttp.open(type,url,true);
        xmlhttp.setRequestHeader("X-CSRF-TOKEN",token);
        await xmlhttp.send(data);
      },

}
const CutImg = {
            name:'',
            $croppCrop:{},
            divActiveImage:'',

            saveCut(nameModal) {

                this.$croppCrop[nameModal].croppie('result', {
                    type: 'base64',
                    size: 'viewport',
                    format: 'jpeg'

                }).then(function(resp) {

                    // $('#img-croping-'+nameModal).attr('src',resp);

                    CutImg.sendCutFile(resp);

                    // $('#'+nameModal).attr('value',resp);
                    CutImg.closeModal();

                });
            },

            imgCroping(id){

                divActiveImage = document.getElementById(id);
                name = divActiveImage.closest('.img-list').dataset.type;
                image = divActiveImage.querySelector('img');
// console.log(image);
                this.$croppCrop[name]= this.setObjectCrop();

                $('.'+name).modal('show');

                this.$croppCrop[name].croppie('bind', {
                        url: image.src
                    }).then(function() {

                        console.log('jQuery bind complete');
                    });
            },

            setObjectCrop(){

                if(this.$croppCrop.hasOwnProperty(name)) {

                    return this.$croppCrop[name];
                }

                let stock = document.getElementById('data-'+name);

                return $('#image-crop-'+name).croppie({
                    enableExif: true,
                    viewport: {
                        width: stock.dataset.widthCut,
                        height: stock.dataset.heightCut,

                    },
                    boundary: {
                        width: '95%',
                        height: 50+Number(stock.dataset.heightCut),
                    }
                });
            },

            sendCutFile(resp){
                let img_cut='';
                let postData = document.getElementById('post-data');
                let stock = document.getElementById('data-'+name);

                let data = JSON.stringify({
                  id:3,
                  table:
                  {
                    name_model: name,
                    user_id: postData.dataset.userId,
                    post_id: postData.dataset.postId,
                  },
                  property:{
                    widthCut:  stock.dataset.widthCut,
                    heightCut: stock.dataset.heightCut,
                    url:image.src,
                    image: resp,
                   },
                });

                // upImgObj = UpImg_obj;
               UpImg_obj.sendAjax(
                  'POST', '/add-cut/'+divActiveImage.dataset.id,
                  this.createCutData(data),
                  function(msg){
                  // console.log(msg);
                    const data = JSON.parse(msg);
                    if(!data.error) {

                      id = divActiveImage.dataset.id;

                      if($('#src_cut_'+id).length){
                        $("#cut_"+id).remove();
                        $('#src_cut_'+id).remove();
                      }



                      if(stock.dataset.onlyCut) {

                        img_cut = '<img id="cut_'+id+'" class="cut-img" src="'+resp+'">';

                        $('#img_'+id).find('.img-upload').addClass('cut-img-hidden');
                      }else{

                        img_cut = '<img id="cut_'+id+'" class="cut-img-hidden shadow-img" src="'+resp+'">';
                      }

                      divActiveImage.insertAdjacentHTML( 'beforeend',
                          img_cut+
                          '<input type="hidden" name="'+name+'_cut['+id+']" value="'+data+'" id="src_cut_'+id+'" class="alt">');
                      $('#img_'+id).find('.img-croping').css('background-image','url(/up-file/image/cropping_green.png)')



                    } else { alert(data.error); }
                });
            },

            createCutData(data){

                const formData = new FormData(); // создаем объект FormData для передачи файла

                formData.append('data', data); // добавляем данные в объект FormData

                return formData;
            },

            closeModal(){
              $('.modal').modal('hide');
              // $('.modal').css('display','none');
            },

            viewCutImage(id){

                // divActiveImage = document.getElementById(id);
                // image = divActiveImage.querySelector('img');
                // id_cut = divActiveImage.dataset.id;
                $elem = $('#img_'+id).find('.img-croping');

                $elem.mousemove(function(e){
                   $('#cut_'+id).css('display','block');
                  var target = this.getBoundingClientRect();
                  var x = e.clientX - target.left+5;
                  var y = e.clientY - target.top+5;

                   $('#cut_'+id).css({'left':x, 'top':y})
                });

                $elem.mouseout(function(e){
                  $('#cut_'+id).css('display','none');
                });
            },
}