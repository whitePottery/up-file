@foreach($images as $image)
  <div class="img-item" id="img_{{ $image->id }}" data-id="{{ $image->id }}">
    <img class="img-upload" id="fullImg_{{ $image->id }}" {{$image->src_cut?'hidden':''}} src="{{ $image->src }}" {{ (0==$image->post_id) ? 'style = opacity:0.5':''; }} onclick="Img_obj.editAlt('img_{{ $image->id }}');" >
    <a herf="#" class="img-delete" onclick="Img_obj.removeImg('img_{{ $image->id }}'); return false;" title="Удалить изображение"></a>
    <a herf="#" class="img-croping {{ $image->src_cut?'img-green':'img-black' }}" onclick="Cut_obj.imgCroping('img_{{ $image->id }}');" title="Сделать превью для картинки" onmouseover="Cut_obj.viewCutImage({{ $image->id }})"></a>
    <input type="hidden" value="{{ $image->alt?$image->alt:'image-'.$image->id }}" id="alt{{ $image->id }}"class="alt">
    @if(!empty($image->src_cut))
    <img id="cut_{{ $image->id }}" class="img-upload" src="{{ $image->src_cut }}">
    @endif
  </div>
@endforeach