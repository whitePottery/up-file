<div class="{{  $class }}">
  <ul>
    @isset($images)
    @foreach($images as $image)
      <li>
        <img src="{{ $image->url }}" alt="{{ $image->alt }}">
      </li>
    @endforeach
    @endisset

  </ul>
</div>
