@foreach($images as $image)
  <div class="img-item" id="img_{{ $image->id }}" data-id="{{ $image->id }}">
    <img class="img-upload" src="{{ $image->url_img }}/{{ $image->name_img }}" {{ (0==$image->post_id) ? 'style = opacity:0.5':''; }} onclick="UpImg_obj.editAlt('img_{{ $image->id }}');" onmouseover="viewCutImage({{ $image->id }})">
    <a herf="#" class="img-delete" onclick="UpImg_obj.removeImg('img_{{ $image->id }}'); return false;" title="Удалить изображение"></a>
    <a herf="#" class="img-croping {{ $image->path_mini?'img-green':'img-black' }}" onclick="imgCroping('img_{{ $image->id }}');" title="Сделать превью для картинки"></a>
    <input type="hidden" value="{{ $image->alt?$image->alt.$image->id:'image-'.$image->id }}" id="alt{{ $image->id }}"class="alt">
    @isset($image->path_mini)
    <img id="cut_{{ $image->id }}" class="cut-img-hidden" src="{{ $image->url_img }}/{{ $image->path_mini }}/{{ $image->name_img }}">
    @endisset
  </div>
@endforeach