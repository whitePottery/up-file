@foreach($images as $image)
  <div class="img-item" id="{{ $image->id }}">
    <img src="{{ $image->url }}" {{ (0==$image->post_id) ? 'style = opacity:0.5':''; }} onclick="UpImg_obj.editAlt({{ $image->id }});">
    <a herf="#" class="img-delete" onclick="UpImg_obj.removeImg({{ $image->id }}); return false;" title="Удалить изображение"></a>
    <a herf="#" class="img-croping" onclick="imgCroping({{ $image->id }});" title="Сделать превью для картинки"></a>
    <input type="hidden" value="{{ $image->alt?$image->alt.$image->id:'image-'.$image->id }}" id="alt{{ $image->id }}"class="alt">
  </div>
@endforeach