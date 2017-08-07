<?php
/**
 * @see http://bxslider.com/
 * @var \Minhbang\Layout\Widget $widget
 * @var \Minhbang\Ebook\Ebook[] $ebooks
 */
?>
@if($ebooks)
    <div class="bxslider slider-ebooks slider-ebooks-{{$widget->id}}">
        @foreach($ebooks as $i => $ebook)
            <?php
            $url = $ebook->url;
            $publisher = trans('ebook::common.publisher_id_th').": {$ebook->publisher_title}, {$ebook->pyear}";
            ?>
            <div class="ebook-slide-item">
                <a href="{{$url}}">
                    <div class="ebook-cover">
                        {!! $ebook->present()->featured_image !!}
                        <div class="security">{!! $ebook->present()->security( 'success' ) !!}</div>
                    </div>
                </a>
                <div class="inner">
                    <a href="{{$url}}">
                        <div class="title">{{$ebook->title}}</div>
                    </a>
                    <div class="details">
                        {{$ebook->writer_title}}<br>
                        {{$publisher}}<br>
                        <small><i class="fa fa-calendar"></i> {{$ebook->present()->updatedAt}}</small>
                        <small><i class="fa fa-eye"></i> {{$ebook->hit}}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('.slider-ebooks').bxSlider({auto: true});
            });
        </script>
    @endpush
@endif
