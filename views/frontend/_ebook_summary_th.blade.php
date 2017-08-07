<?php /** @var \Minhbang\Ebook\Ebook $ebook */ ?>
<a href="{{$ebook->url}}" class="ebook-th-item">
    <div class="ebook-cover">
        {!! $ebook->present()->featured_image !!}
        <div class="details">
            <div class="inner">
                {{$ebook->writer_title}}<br>
                {!! $ebook->present()->fileicon !!} {!! $ebook->present()->filesize !!}
                <i class="fa fa-eye"></i> {{$ebook->hit}}
            </div>
        </div>
        <div class="security">{!! $ebook->present()->security( 'success' ) !!}</div>
    </div>
    <div class="title">{!! $ebook->title !!}</div>
</a>