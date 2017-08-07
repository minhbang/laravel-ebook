<?php
/**
 * @var \Minhbang\Layout\Widget $widget
 * @var \Minhbang\Ebook\Ebook[] $ebooks
 */
$css = empty($widget->data['item_css']) ? [] : explode('|', $widget->data['item_css']);
$css_len = count($css);
$has_col = ! empty($css[0]) && str_is("*col-*", ' '.$css[0]);
?>
@if($ebooks)
    <div class="ebooks{{$has_col ? ' row':''}}">
        @foreach($ebooks as $i => $ebook)
            <div class="{{ $css_len ? ' '.$css[$i % $css_len]: '' }}">
                @include("ebook::frontend._ebook_summary_{$widget->data['item_style']}", compact('ebook'))
            </div>
        @endforeach
    </div>
@endif