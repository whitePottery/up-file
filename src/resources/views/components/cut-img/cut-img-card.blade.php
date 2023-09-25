@foreach($images as $image)
  <div class="img-item" id="img_{{ $image->id }}" data-id="{{ $image->id }}">
    <img class="img-upload {{ ($image->src_cut && $onlyCut)?'cut-img-hidden':'' }}" src="{{ $image->src }}" {{ (0==$image->post_id) ? 'style = opacity:0.5':''; }} onclick="UpImg_obj.editAlt('img_{{ $image->id }}');" >
    <a herf="#" class="img-delete" onclick="UpImg_obj.removeImg('img_{{ $image->id }}'); return false;" title="Удалить изображение"></a>
    <a herf="#" class="img-croping {{ $image->src_cut?'img-green':'img-black' }}" onclick="CutImg.imgCroping('img_{{ $image->id }}');" title="Сделать превью для картинки" {{ ($onlyCut)?'':'onmouseover=CutImg.viewCutImage('.$image->id.')' }}></a>
    <input type="hidden" value="{{ $image->alt?$image->alt:'image-'.$image->id }}" id="alt{{ $image->id }}"class="alt">
    @if(!empty($image->src_cut))
        <img id="cut_{{ $image->id }}" class="cut-img {{ ($onlyCut)?'':'cut-img-hidden shadow-img' }}" src="{{ $image->src_cut }}">
        <input type="hidden" name="src_cut[{{ $image->id }}]" value="{{ $image->src_cut }}" id="src_cut[{{ $image->id }}]" class="alt">
    @endif
        <input type="hidden" name="src_[{{ $image->id }}]" value="{{ $image->src }}" id="src_[{{ $image->id }}]" class="alt">
  </div>
@endforeach

      {{-- @if($onlyCut) --}}