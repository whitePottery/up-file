    <div>
        <img height="200" src="{{ old('image_base64-'.$typePage)??'/images/no_photo.jpg'}}" id="img-croping-{{ $typePage }}" alt="{{ __('upfile.preview_image') }}">
    </div>
@once
    <div>
        <div class="modal" tabindex="-1">
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
                            <div id="image-crop">
                            </div>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">
                            {{ __('upfile.close_window') }}
                        </button>
                        <button class="btn btn-primary image-result" type="button">
                            {{ __('upfile.save_changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('css')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css" rel="stylesheet">
    @endpush

    @push('js_scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

        <script type="text/javascript">

            let typePage='';

            $('input[name="image"]').attr("id", "image");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $croppCrop = $('#image-crop').croppie({
                enableExif: true,
                viewport: {
                    width: {{ $widthCropp??296 }},
                    height: {{ $heightCropp??219 }},
                    {!! isset($type)?"type:'$type',":'' !!}
                },
                boundary: {
                    width: '95%',
                    height: {{ ($heightCropp??250)+50 }},
                }
            });

            $('.image-result').on('click', function(ev) {
                $croppCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(resp) {

                    $('#img-croping-'+typePage).attr('src',resp);

                    $('.modal').modal('hide');

                  $('<input type="hidden" id="image_base64_'+typePage+'" name="image_base64['+typePage+']" value="'+resp+'">').appendTo('#form');



                });
            });

            function imgCroping(id){
              $('.modal').modal('show');

                divActiveImage = document.getElementById(id);
                typePage = divActiveImage.closest('.img-list').dataset.type;
                image = divActiveImage.querySelector('img');
                // console.log(image.src);
                 $croppCrop.croppie('bind', {
                        url: image.src
                    }).then(function() {

                        console.log('jQuery bind complete');
                    });

            }
         </script>
    @endpush
@endonce