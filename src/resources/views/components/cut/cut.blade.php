{{-- сохранять в сессию или Redis --}}
    <div id="data-{{ $nameCut }}" data-width="{{ $widthCut }}" data-height="{{ $heightCut }}">
        <div><img height="200" src="{{ $src??'/images/no_photo.jpg'}}" id="img-croping-{{ $nameCut }}" alt="{{ __('upfile.preview_image') }}">
        <input type="hidden" id="{{ $nameCut }}" name="{{ $nameCut }}" value="">

{{--         <input type="hidden" id="image_srs_{{ $nameCut }}'" name="image_src_{{ $nameCut }}" value=""> --}}
    </div></div>

    <div>
        <div class="modal {{ $nameCut }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
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

                    $('#'+nameModal).attr('value',resp);

                    $('.modal').modal('hide');
                });
            }

            function imgCroping(id){

                let divActiveImage = document.getElementById(id);
                name = divActiveImage.closest('.img-list').dataset.type;
                let image = divActiveImage.querySelector('img');

                $croppCrop[name]= setObjectCrop();

                $('.'+name).modal('show');

                $croppCrop[name].croppie('bind', {
                        url: image.src
                    }).then(function() {

                        console.log('jQuery bind complete');
                    });
            }

            function setObjectCrop(){

                if($croppCrop.hasOwnProperty(name)) return;

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
         </script>
    @endpush
@endonce
