{{-- сохранять в сессию или Redis --}}
{{--     <div id="data-{{ $nameModel }}" data-width="{{ $widthCut }}" data-height="{{ $heightCut }}">
        <div><img height="200" src="{{ $src??'/images/no_photo.jpg'}}" id="img-croping-{{ $nameModel }}" alt="{{ __('upfile.preview_image') }}">
        <input type="hidden" id="{{ $nameModel }}" name="{{ $nameModel }}" value="">
        </div>
    </div> --}}
    <div id="data-{{ $nameModel }}" data-width-cut="{{ $widthCut }}" data-height-cut="{{ $heightCut }}" data-width-img="{{ $widthImg }}" data-height-img="{{ $heightImg }}" data-only-cut="{{ $onlyCut }}">
      <div class="form-row">
        <label>{{ __('upfile::upfile.images.'.$nameModel ) }}:</label>
          <div class="img-list" id="js-file-list-{{ $nameModel }}" data-type="{{ $nameModel }}">{!! $images??'' !!}</div>
          <input id="{{ $nameModel }}" type="file" name="file" enctype="multipart/form-data" accept=".jpg,.jpeg,.png,.gif" onchange="UpImg_obj.sendFile(this);">
      </div>
    </div>

    <div>
        <div class="modal {{ $nameModel }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('upfile::upfile.cut_image') }}
                        </h5>
                        <button aria-label="Закрыть" class="btn-close" data-bs-dismiss="modal" type="button">
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            <div id="image-crop-{{ $nameModel }}">
                            </div>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">
                            {{ __('upfile.close_window') }}
                        </button>
                        <button class="btn btn-primary image-result" type="button" onclick="CutImg.saveCut('{{ $nameModel }}')">
                            {{ __('upfile.save_changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@once



    <div class="modal-alt" tabindex="-1"  id="alt-text">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Текст Alt</h5>
            <button type="button" class="btn-close"  aria-label="Закрыть" onclick="UpImg_obj.closeModalAlt()"></button>
          </div>
          <div class="modal-body">
            <p>Введите текст для тега Alt изображения</p>
          </div>
          <div class="modal-body">
            <input type="text" class="form-control" value="">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="UpImg_obj.closeModalAlt()">Закрыть</button>
            <button type="button" class="btn btn-primary" onclick="UpImg_obj.saveModalAlt()">Сохранить изменения</button>
          </div>
        </div>
      </div>
    </div>

    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css" rel="stylesheet">

            <style>
    /* Стили для вывода превью */
    .img-item {

      display: inline-block;
      margin: 0 20px 20px 0;
      position: relative;
      user-select: none;
    }
    .img-item .img-upload {
      border: 1px solid #767676;
      height: 200px;
    }
    .img-item .img-delete {
      display: inline-block;
      background: url(/remove.png) 0 0 no-repeat;
      position: absolute;
      top: -5px;
      right: -9px;
      width: 20px;
      height: 20px;
      cursor: pointer;
    }
    .img-item .img-croping {
      display: inline-block;

      position: absolute;
      top: -5px;
      left: -9px;
      width: 40px;
      height: 40px;
      cursor: pointer;
    }
    .img-item .img-black {
      background: url(/cropping.png) 0 0 no-repeat;
    }
    .img-item .img-green {
      background: url(/cropping_green.png) 0 0 no-repeat;
    }

    .modal-alt{
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1060;
        display: none;
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
      }
    .modal-alt-input{
        width: 90%;
    }

    .img-item .cut-img{
      border: 1px solid #767676;
      height: 150px;

    }
    .img-item .shadow-img{
      box-shadow: 10px 5px 5px grey;
    }

    .img-item .cut-img-hidden{
      display:none;
      position: absolute;
      max-width: 300px;
      max-height: 300px;
      z-index: 99;
    }
    </style>
    @endpush

    @push('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

        <script type="text/javascript">
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
        let stock = document.getElementById('data-'+nameModel);


// console.log(stock);
        let data = JSON.stringify({
          table:
          {
            name_model: nameModel,
            // name_img: image.url.split('/').pop(),
            user_id: '{{ $user_id }}',
            post_id: '{!! $postId??'0' !!}',
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

        let token = document.querySelector('meta[name="csrf-token"]').content

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
                    type: 'canvas',
                    size: 'viewport',
                    format: 'jpeg'

                }).then(function(resp) {

                    // $('#img-croping-'+nameModal).attr('src',resp);

                    CutImg.sendCutFile(resp);

                    // $('#'+nameModal).attr('value',resp);

                    $('.modal').modal('hide');
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

                        {!! isset($type)?"type:'$type',":'' !!}
                    },
                    boundary: {
                        width: '95%',
                        height: 50+Number(stock.dataset.heightCut),
                    }
                });
            },

            sendCutFile(resp){
                let img_cut='';

                let stock = document.getElementById('data-'+name);

                let data = JSON.stringify({
                  id:3,
                  table:
                  {
                    name_model: name,
                    user_id: '{{ $user_id }}',
                    post_id: '{!! $postId??'0' !!}',
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
                      $('#img_'+id).find('.img-croping').css('background-image','url(/cropping_green1.png)')



                    } else { alert(data.error); }
                });
            },

            createCutData(data){

                const formData = new FormData(); // создаем объект FormData для передачи файла

                formData.append('data', data); // добавляем данные в объект FormData

                return formData;
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

         </script>
    @endpush
@endonce
