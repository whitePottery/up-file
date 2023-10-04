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
                        <button aria-label="Закрыть" class="btn-close" onclick="CutImg.closeModal()" type="button">
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            <div id="image-crop-{{ $nameModel }}">
                            </div>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" onclick="CutImg.closeModal()" type="button">
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

<span id="post-data"
  data-user-id="{{ $user_id??'0'}}"
  data-post-id="{!! $postId??'0' !!}"
></span>

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
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css" rel="stylesheet"> --}}
        <link href="/up-file/css/croppie/2.6.2/croppie.min.css" rel="stylesheet">

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
      background: url(/up-file/image/remove.png) 0 0 no-repeat;
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
      background: url(/up-file/image/cropping.png) 0 0 no-repeat;
    }
    .img-item .img-green {
      background: url(/up-file/image/cropping_green.png) 0 0 no-repeat;
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
        <script src="/up-file/js/jquery/2.1.1/jquery.min.js"></script>

        <script src="/up-file/js/croppie/2.6.2/croppie.js"></script>

        <script src="/up-file/js/up_img.js"></script>


    @endpush
@endonce
