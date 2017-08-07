<?php /** @var \Minhbang\Ebook\Ebook $ebook */
$ds = '';
$ds .= '<dt>'.trans("ebook::common.language_id").'</dt><dd>'.$ebook->language_title.'</dd>';
$ds .= '<dt>'.trans("ebook::common.pages").'</dt><dd>'.$ebook->pages.'</dd>';

$url = $ebook->url;
$publisher = trans("ebook::common.publisher_id_th").': '.$ebook->publisher_title;
?>
<div class="ebook-list-item">
    <a href="{{$url}}">
        <div class="ebook-cover">
            {!! $ebook->present()->featured_image !!}
            <div class="security">{!! $ebook->present()->security( 'success' ) !!}</div>
        </div>
    </a>
    <div class="inner">
        <blockquote>
            <a href="{{$url}}">
                <div class="title">{{$ebook->title}}</div>
            </a>
            <footer>{{$ebook->title}}, {{$publisher}}, {{$ebook->pyear}}</footer>
        </blockquote>

        <div class="details">
            <dl class="dl-horizontal">{!! $ds !!}</dl>
            <small>{!! $ebook->present()->fileicon !!} {{$ebook->present()->filesize}}</small>
        </div>
    </div>
</div>