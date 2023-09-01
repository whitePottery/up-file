<div>
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
    <div class="form-row">
      <label>Изображения:</label>
      <div class="img-list" id="js-file-list"></div>
      <input id="js-file" type="file" name="file" enctype="multipart/form-data" accept=".jpg,.jpeg,.png,.gif">
    </div>
{{--     <input type="text" value="test" name="path_mini_img"> --}}
    {{-- <input type="hidden" id="route-name" value="{{Route::currentRouteName();}}" name="route-name"> --}}
{{--     <input type="hidden" id="route-name" value="{{Route::currentRouteName();}}" name="route-name"> --}}

<div class="modal-alt" tabindex="-1"  id="alt-text">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Заголовок модального окна</h5>
        <button type="button" class="btn-close"  aria-label="Закрыть" onclick="closeModalAlt()"></button>
      </div>
      <div class="modal-body">
        <p>Здесь идет основной текст модального окна</p>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModalAlt()">Закрыть</button>
        <button type="button" class="btn btn-primary" onclick="saveModalAlt()">Сохранить изменения</button>
      </div>
    </div>
  </div>
</div>
<script>

  let typePage = {!! $typePage !!};



  let divActiveImage = '';
  let inputAlt = '';


  let inputFile = document.getElementById('js-file');

  // const routeName = document.getElementById('route-name').value;


// if(0!=images) {
//         images.forEach( function(item, index, array) {
// // console.log(item.url);
//           addHiddenInput(item.id, item.url, item.alt)
//         });
// }



  loadImageTmp();





  inputFile.onchange = function() {

      sendFile(inputFile);
  }
  /*

  */
  function loadImageTmp(){

    sendAjax('GET', '/get-image/'+typePage+'/'+{!! $postId??'0' !!} , '', function(msg){

      const data = JSON.parse(msg);

      if(!data.error) {

        data.images.forEach( function(item, index, array) {

          addHiddenInput(item.id, item.url, item.alt, item.post_id)
        });
      }

    });

  }
  /*

  */
  function sendFile(inputFile){

    sendAjax('POST', '/add-image/'+typePage+'/'+{!! $postId??'0' !!} , createImageData(inputFile), function(msg){
      // console.log(msg);
      const data = JSON.parse(msg);

      if(!data.error) {
        addHiddenInput(data.id, data.url);
        inputFile.value = '';
      } else { alert(data.error); }

    });

  }
  function sendAlt(alt){
// console.log(alt.value);

    // divActiveImage = document.getElementById(id);
    // inputAlt = divActiveImage.querySelector('.alt');
    const formData = new FormData();

    // formData.append('model_img', divActiveImage.dataset.modelImg);
    formData.append('id', divActiveImage.id);
    formData.append('alt', alt.value);


    sendAjax('POST', '/add-image-alt', formData, function(msg){
// console.log(msg);
      const data = JSON.parse(msg);

    });

  }
  /*

   */
  function addHiddenInput(id, url, alt='', post_id=0){

    let divImg = document.getElementById('js-file-list');
    let tmpStyle = post_id?'':'style = "opacity:0.5"';
    let altValue = alt??'image-'+id;
    let HtmlCode = `<div class="img-item" id="`+id+`">
    <img src="`+url+`" `+tmpStyle+`onclick="editAlt(`+id+`);">
            <a herf="#" class="img-delete" onclick="removeImg(`+id+`); return false;" title="Удалить изображение"></a>
            <a herf="#" class="img-croping" onclick="imgCroping(`+id+`);" title="Сделать превью для картинки"></a>
            <input type="hidden" value="`+altValue+`" id="alt`+id+`"class="alt">
         </div>
          `;

    divImg.insertAdjacentHTML( 'beforeend', HtmlCode );
  }
  /*

 */
  function editAlt(id){

    divActiveImage = document.getElementById(id);
    inputAlt = divActiveImage.querySelector('.alt');


    let modal = document.getElementById('alt-text');
    let modalDialog = modal.querySelector('.modal-dialog');
    let inputModal = modal.querySelector('input');

    inputModal.value = inputAlt.value;
// console.log(inputModal);
    var rect = divActiveImage.getBoundingClientRect();



    modal.style.display='block';
    inputModal.focus();
    modalDialog.style.top = rect.top-300+"px";
    divActiveImage.style.border = "#FF0000FF 7px solid";
  }
  /*

   */
  function saveModalAlt(){

        inputAlt = divActiveImage.querySelector('.alt');


        let modal = document.getElementById('alt-text');
        let inputModal = modal.querySelector('input');


// console.log(divActiveImage);


    if(inputModal.value.length>0){
      inputAlt.value = inputModal.value;
            sendAlt(inputModal);
         inputModal  = '';
    }

    closeModalAlt();
  }
/*

 */
  function closeModalAlt(){

    let modal = document.getElementById('alt-text');

    modal.style.display='none';

    divActiveImage.style.border = "none";
  }

  /*
  Удаление загруженной картинки
  */
  function removeImg(id){

    divActiveImage = document.getElementById(id);
// console.log(divActiveImage);
    const formData = new FormData();
    // formData.append('model_img', divActiveImage.dataset.modelImg);
    formData.append('id', id);



    sendAjax('POST', '/del-image', formData, function(msg){
// console.log(msg);
      const data = JSON.parse(msg);

      if(!data.error) {
        document.getElementById(id).remove();
      }

    });

  }


  function createImageData(inputFile){

    const file = inputFile.files[0]; // получаем выбранный файл

    const formData = new FormData(); // создаем объект FormData для передачи файла

    formData.append('image', file); // добавляем файл в объект FormData

    return formData;
  }
  /*
  отправка ajax запросов на сервер
   */
  function sendAjax(type, url, data, callback){

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
    xmlhttp.send(data);
  }

</script>

</div>