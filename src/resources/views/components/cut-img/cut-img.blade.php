{{-- сохранять в сессию или Redis --}}
{{--     <div id="data-{{ $nameCut }}" data-width="{{ $widthCut }}" data-height="{{ $heightCut }}">
        <div><img height="200" src="{{ $src??'/images/no_photo.jpg'}}" id="img-croping-{{ $nameCut }}" alt="{{ __('upfile.preview_image') }}">
        <input type="hidden" id="{{ $nameCut }}" name="{{ $nameCut }}" value="">
        </div>
    </div> --}}
    <div>
      <div class="form-row">
        <label>Изображения:</label>
          <div class="img-list" id="js-file-list-{{ $nameImg }}" data-type="{{ $nameImg }}">{!! $images??'' !!}</div>
          <input id="{{ $nameImg }}" type="file" name="file" enctype="multipart/form-data" accept=".jpg,.jpeg,.png,.gif" onchange="UpImg_obj.sendFile(this);">
      </div>
    </div>

    <div>
        <div class="modal {{ $nameCut }}" tabindex="-1">
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
                            <div id="image-crop-{{ $nameCut }}">
                            </div>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">
                            {{ __('upfile.close_window') }}
                        </button>
                        <button class="btn btn-primary image-result" type="button" onclick="saveCut('{{ $nameCut }}')">
                            {{ __('upfile.save_changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

@once
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

        <script type="text/javascript">

            let name='';
            let $croppCrop={};

            $('input[name="{{ $nameCut }}"]').attr("id", "{{ $nameCut }}");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function saveCut(nameModal) {

                $croppCrop[nameModal].croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(resp) {

                    $('#img-croping-'+nameModal).attr('src',resp);

                    sendCutFile(resp);
                    $('#'+nameModal).attr('value',resp);

                    $('.modal').modal('hide');
                });
            }

            function imgCroping(id){

                let divActiveImage = document.getElementById(id);
                name = divActiveImage.closest('.img-list').dataset.type;
                image = divActiveImage.querySelector('img');

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
                    url:image.src
                    image: resp,
                   },
                });

                UpImg_obj.sendAjax(
                  'POST', '/add-image',
                  createCutData(data),
                  function(msg){
                  console.log(msg);
                    const data = JSON.parse(msg);
                    if(!data.error) {

                      let divImg = document.getElementById('js-file-list-'+name);

                      // divImg.insertAdjacentHTML( 'beforeend', data.image );
image.src = resp;//$('#img-croping-'+nameModal).attr('src',resp);


                    } else { alert(data.error); }
                });
            }

            function createCutData(data){

                const formData = new FormData(); // создаем объект FormData для передачи файла

                formData.append('data', data); // добавляем данные в объект FormData

                return formData;
            }
         </script>
    @endpush
@endonce
