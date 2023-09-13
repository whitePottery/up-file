{{-- Секция вывода изображений и кнопки добавления изображений  --}}
<div>
  <x-upfile-cut-img name="{{ $nameImg }}" width="{{ $widthCut }}" height="{{ $heightCut }}"/>
  <div class="form-row">
    <label>Изображения:</label>
      <div class="img-list" id="js-file-list-{{ $nameImg }}" data-type="{{ $nameImg }}">{!! $images??'' !!}</div>
      <input id="{{ $nameImg }}" type="file" name="file" enctype="multipart/form-data" accept=".jpg,.jpeg,.png,.gif" onchange="UpImg_obj.sendFile(this);">
  </div>
</div>



@once
  {{-- <input type="hidden" name="nameModel" value="{{ $nameModel }}"> --}}
{{-- блок модальное окно для изменения Alt изображения --}}
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
    <style>
    /* Стили для вывода превью */
    .img-item {

      display: inline-block;
      margin: 0 20px 20px 0;
      position: relative;
      user-select: none;
    }
    .img-item img {
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
    </style>
  @endpush

  @push('scripts')
    <script>
    const UpImg_obj = {

      divActiveImage : '',
      inputAlt : '',

      /**
       * [sendFile description]
       * @param  {[type]} inputFile [description]
       * @return {[type]}           [description]
       */
      sendFile(inputFile){

        let nameImg=inputFile.id;

        let data = JSON.stringify({
          table:
          {
            name_img: nameImg,
            name_model: '{{ $nameModel }}',
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
        formData.append('id', divActiveImage.id);
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

        const formData = new FormData();

        formData.append('id', id);

        UpImg_obj.sendAjax('POST', '/del-image', formData, function(msg){

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
    </script>
  @endpush
@endonce


