{{-- сохранять в сессию или Redis --}}
{{--     <div id="data-{{ $nameModel }}" data-width="{{ $widthCut }}" data-height="{{ $heightCut }}">
        <div><img height="200" src="{{ $src??'/images/no_photo.jpg'}}" id="img-croping-{{ $nameModel }}" alt="{{ __('upfile.preview_image') }}">
        <input type="hidden" id="{{ $nameModel }}" name="{{ $nameModel }}" value="">
        </div>
    </div> --}}
    <div id="coord-live"></div>
    <div id="data-{{ $nameModel }}" data-width="{{ $widthCut }}" data-height="{{ $heightCut }}">
      <div class="form-row">
        <label>Изображения:</label>
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
                            {{ __('upfile.cut_image') }}
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
                        <button class="btn btn-primary image-result" type="button" onclick="saveCut('{{ $nameModel }}')">
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
            <h5 class="modal-title">Заголовок модального окна</h5>
            <button type="button" class="btn-close"  aria-label="Закрыть" onclick="UpImg_obj.closeModalAlt()"></button>
          </div>
          <div class="modal-body">
            <p>Здесь идет основной текст модального окна</p>
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
      background: url(/cropping.png) 0 0 no-repeat;
      position: absolute;
      top: -5px;
      left: -9px;
      width: 40px;
      height: 40px;
      cursor: pointer;
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
    .img-item .cut-img-hidden{
      display:none;
      position: absolute;
      max-width: 300px;
      max-height: 300px;
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
// console.log(nameModel);
        let data = JSON.stringify({
          table:
          {
            name_model: nameModel,
            // name_img: image.url.split('/').pop(),
            user_id: '{{ $user_id }}',
            post_id: '{!! $postId??'0' !!}',
          },
          property:{
            widthImg:  {{ $widthImg }},
            heightImg: {{ $heightImg }},

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
        var rect = divActiveImage.getBoundingClientRect();
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

            let name='';
            let $croppCrop={};

            // $('input[name="{{ $nameModel }}"]').attr("id", "{{ $nameModel }}");

            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });

            function saveCut(nameModal) {

                $croppCrop[nameModal].croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(resp) {

                    // $('#img-croping-'+nameModal).attr('src',resp);

                    sendCutFile(resp);

                    // $('#'+nameModal).attr('value',resp);

                    $('.modal').modal('hide');
                });
            }

            function imgCroping(id){

                divActiveImage = document.getElementById(id);
                name = divActiveImage.closest('.img-list').dataset.type;
                image = divActiveImage.querySelector('img');
// console.log(image);
                $croppCrop[name]= setObjectCrop();

                $('.'+name).modal('show');

                $croppCrop[name].croppie('bind', {
                        url: image.src
                    }).then(function() {

                        console.log('jQuery bind complete');
                    });
            }

            function setObjectCrop(){

                if($croppCrop.hasOwnProperty(name)) {

                    return $croppCrop[name];
                }

                let stock = $('#data-'+name);

                return $('#image-crop-'+name).croppie({
                    enableExif: true,
                    viewport: {
                        width: stock.data("width"),
                        height: stock.data("height"),

                        {!! isset($type)?"type:'$type',":'' !!}
                    },
                    boundary: {
                        width: '95%',
                        height: stock.data("height")+50 ,
                    }
                });
            }

            function sendCutFile(resp){



                let data = JSON.stringify({
                  table:
                  {
                    name_img: name,
                    name_model: '{{ $nameModel??'test' }}',
                    user_id: '{{ $user_id??1 }}',
                    post_id: '{!! $postId??'0' !!}',
                  },
                  property:{
                    widthImg:  {{ $widthImg??200 }},
                    heightImg: {{ $heightImg??100 }},
                    url:image.src,
                    image: resp,
                   },
                });

                UpImg_obj.sendAjax(
                  'POST', '/add-cut',
                  createCutData(data),
                  function(msg){
                  // console.log(msg);
                    const data = JSON.parse(msg);
                    if(!data.error) {

                  id = divActiveImage.dataset.id;


                  $("#cut_"+id).remove();

                  divActiveImage.insertAdjacentHTML( 'beforeend',
                        '<img id="cut_'+id+'" class="cut-img-hidden" src="'+resp+'">' );

                      // let divImg = document.getElementById('js-file-list-'+name);

                      // divImg.insertAdjacentHTML( 'beforeend', data.image );
// image.src = resp;

//$('#img-croping-'+nameModal).attr('src',resp);


                    } else { alert(data.error); }
                });
            }

            function createCutData(data){

                const formData = new FormData(); // создаем объект FormData для передачи файла

                formData.append('data', data); // добавляем данные в объект FormData

                return formData;
            }


            function viewCutImage(id){

                divActiveImage = document.getElementById(id);;
                // image = divActiveImage.querySelector('img');
                id_cut = divActiveImage.dataset.id;


                $('#'+id).find('.img-croping').mousemove(function(e){
                   $('#cut_'+id_cut).css('display','block');
                  var target = this.getBoundingClientRect();
                  var x = e.clientX - target.left+5;
                  var y = e.clientY - target.top+5;
                  $('#coord-live').html(x + ', ' + y);
                   $('#cut_'+id_cut).css({'left':x, 'top':y})
                });
$('#cut_'+id_cut).css('display','none');

//               $('#'+id).find('img:first-child').mousemove(function(e){

//               $('#cut_'+id_cut).css('display','block');

//                   var pos=$(this).position();
// console.log(e.pageX);
//                   $('#cut_'+id_cut).css({'left':e.pageX})
//                   // $('#xCoord').val(e.pageX-pos.left);
//                   // $('#yCoord').val(e.pageY-pos.top);
//               });


            }

         </script>
    @endpush
@endonce
