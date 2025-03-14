@php
      $picture_hierarchy = [
        [
            "key" => "mobile_image",
            "src" => null,
            "media" => null
        ],
        [
            "key" => "tablet_image",
            "src" => null,
            "media" => "(min-width: 768px)"
        ],
        [
            "key" => "desktop_image",
            "src" => null,
            "media" => "(min-width: 1024px)"
        ],
    ];

    if (!function_exists('setUpPicture')) {
        function setUpPicture($block, &$arr, $setMediaNull = false, $i = 0) {
            if ($i > array_key_last($arr)) {
                return $arr;
            }
            if ($setMediaNull) {
                $arr[$i]['media'] = null;
            }
            if (!$block->hasImage($arr[$i]['key'])) {
                unset($arr[$i]);
                return  setUpPicture($block, $arr, true, $i + 1);
            }
            $arr[$i]['src'] = $block->image($arr[$i]['key']);
            return setUpPicture($block, $arr, false, $i + 1);
        }
    }
    $picture_hierarchy = setUpPicture($block, $picture_hierarchy);

@endphp
<section>
    <div class="container onlyimage">

      <picture>
        @for($i = array_key_last($picture_hierarchy); $i >= 0; $i--)
            @php
                $item = $picture_hierarchy[$i] ?? null;
                if (!$item) {
                    break;
                }
            @endphp

            @if($item['media'])
                <source srcset="{{ $item['src'] }}" media="{{ $item['media'] }}">
            @else
                <img src="{{ $item['src'] }}" alt="{{$block->imagesAsArrays($item['key'])[0]['alt']}}">
            @endif

        @endfor
    </picture>
    </div>
</section>
