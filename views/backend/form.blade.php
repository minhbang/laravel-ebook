@extends($layout)
@section('content')
    {!! Form::model($ebook, ['files' => true, 'url'=>$url, 'method' => $method, 'id' => 'ebook-form']) !!}
    <div class="row">
        <div class="col-lg-7">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>{!! trans('ebook::common.main_info') !!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="form-group{{ $errors->has("title") ? ' has-error':'' }}">
                        {!! Form::label("title", trans('ebook::common.title'), ['class' => "control-label"]) !!}
                        {!! Form::text("title", null, ['class' => 'has-slug form-control',
                        'data-slug_target' => "#title-slug"]) !!}
                        @if($errors->has("title"))
                            <p class="help-block">{{ $errors->first("title") }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has("slug") ? ' has-error':'' }}">
                        {!! Form::label("slug", trans('ebook::common.slug'), ['class' => "control-label"]) !!}
                        {!! Form::text("slug", null, ['id'=>"title-slug", 'class' => 'form-control']) !!}
                        @if($errors->has("slug"))
                            <p class="help-block">{{ $errors->first("slug") }}</p>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has("filename") ? ' has-error':'' }}">
                        {!! Form::label("filename", trans('ebook::common.filename'), ['class' => "control-label"]) !!}
                        {!! Form::fileinput("filename", ['prompt'=>$file_hint]) !!}
                        @if($errors->has("filename"))
                            <p class="help-block">{{ $errors->first("filename") }}</p>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has("summary") ? ' has-error':'' }}">
                        {!! Form::label("summary", trans('ebook::common.summary'), ['class' => "control-label"]) !!}
                        {!! Form::textarea("summary", null, [
                            'class' => 'form-control wysiwyg',
                            'data-editor' => 'simple',
                            'data-height' => 500,
                            'data-attribute' => 'summary',
                            'data-resource' => 'ebook',
                            'data-id' => $ebook->id
                        ]) !!}
                        @if($errors->has("summary"))
                            <p class="help-block">{{ $errors->first("summary") }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>{!! trans('ebook::common.additional_info') !!}</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12 col-md-7">
                            <div class="form-group{{ $errors->has('featured') ? ' has-error':'' }}">
                                {!! Form::label('featured',  trans('ebook::common.featured_ebook'), ['class'
                                => 'control-label']) !!}<br>
                                {!! Form::checkbox('featured', 1, null,['class'=>'switch', 'data-on-text'=>trans('common.yes'), 'data-off-text'=>trans('common.no')]) !!}
                                @if($errors->has('featured'))
                                    <p class="help-block">{{ $errors->first('featured') }}</p>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('category_id') ? ' has-error':'' }}">
                                {!! Form::label('category_id', trans('category::common.category'), ['class' => 'control-label']) !!}
                                {!! Form::select('category_id', $categories, null, ['prompt' =>'', 'class' => 'form-control selectize-tree']) !!}
                                @if($errors->has('category_id'))
                                    <p class="help-block">{{ $errors->first('category_id') }}</p>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group{{ $errors->has('security_id') ? ' has-error':'' }}">
                                        {!! Form::label('security_id', trans('ebook::common.security_id'), ['class' => 'control-label']) !!}
                                        {!! Form::select('security_id', $securities, null, ['prompt'=>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('security_id'))
                                            <p class="help-block">{{ $errors->first('security_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('writer_id') ? ' has-error':'' }}">
                                        {!! Form::label('writer_id', trans('ebook::common.writer_id'), ['class' => 'control-label']) !!}
                                        {!! Form::select('writer_id', $writers, null, ['data-creatable'=>true, 'prompt'=>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('writer_id'))
                                            <p class="help-block">{{ $errors->first('writer_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('pages') ? ' has-error':'' }}">
                                        {!! Form::label('pages', trans('ebook::common.pages'), ['class' =>
                                        'control-label'])
                                         !!}
                                        {!! Form::text('pages', null, ['class' => 'form-control']) !!}
                                        @if($errors->has('pages'))
                                            <p class="help-block">{{ $errors->first('pages') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('pyear') ? ' has-error':'' }}">
                                        {!! Form::label('pyear', trans('ebook::common.pyear'), ['class' => 'control-label']) !!}
                                        {!! Form::text('pyear', null, ['class' => 'form-control']) !!}
                                        @if($errors->has('pyear'))
                                            <p class="help-block">{{ $errors->first('pyear') }}</p>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group{{ $errors->has('language_id') ? ' has-error':'' }}">
                                        {!! Form::label('language_id', trans('ebook::common.language_id'), ['class' => 'control-label']) !!}
                                        {!! Form::select('language_id', $languages, null, ['data-creatable'=>true, 'prompt' =>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('language_id'))
                                            <p class="help-block">{{ $errors->first('language_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('publisher_id') ? ' has-error':'' }}">
                                        {!! Form::label('publisher_id', trans('ebook::common.publisher_id'), ['class' => 'control-label']) !!}
                                        {!! Form::select('publisher_id', $publishers, null, ['data-creatable'=>true, 'prompt'=>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('publisher_id'))
                                            <p class="help-block">{{ $errors->first('publisher_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('pplace_id') ? ' has-error':'' }}">
                                        {!! Form::label('pplace_id', trans('ebook::common.pplace_id'), ['class' =>'control-label']) !!}
                                        {!! Form::select('pplace_id', $pplaces, null, ['data-creatable'=>true, 'prompt'=>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('pplace_id'))
                                            <p class="help-block">{{ $errors->first('pplace_id') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-5">
                            <div class="form-group form-image{{ $errors->has('image') ? ' has-error':'' }}">
                                {!! Form::label('image', trans('ebook::common.featured_image'), ['class' =>
                                'control-label']) !!}
                                {!! Form::selectImage('image', ['thumbnail' => [
                                    'url' => $ebook->featured_image_url,
                                    'width' => $ebook->config['featured_image']['width'],
                                    'height' => $ebook->config['featured_image']['height']
                                ]]) !!}
                                @if($errors->has('image'))
                                    <p class="help-block">{{ $errors->first('image') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-content">
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success save" style="margin-right: 15px;">{{ trans('common.save') }}</button>
                @if(user()->hasRole('tv.nv', true) && $ebook->status < $ebook->statusManager()->valueStatus('pending'))
                    <button type="submit" class="btn btn-primary save_pending" style="margin-right: 15px;">
                        {{ trans('ebook::common.save_pending')}}</button>
                @endif
                @if(user()->hasRole('tv.pt') && $ebook->status < $ebook->statusManager()->valueStatus('published'))
                    <button type="submit" class="btn btn-warning save_published" style="margin-right: 15px;">
                        {{ trans('ebook::common.save_published')}}</button>
                @endif
                <a href="{{ route('backend.ebook.index') }}" class="btn btn-white">{{ trans('common.cancel') }}</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.wysiwyg').mbEditor({
                //upload image
                imageUploadURL: '{!! route('image.store') !!}',
                imageMaxSize: {{setting('system.max_image_size') * 1024 * 1024 }}, //bytes
                // load image
                imageManagerLoadURL: '{!! route('image.data') !!}',
                // custom options
                imageDeleteURL: '{!! route('image.delete') !!}'
            });

            var url = '{!! $url !!}';
            $(".save_pending").on("click", function (e) {
                e.preventDefault();
                $('#ebook-form').attr('action', url + '?s=pending').submit();
            });
            $(".save_published").on("click", function (e) {
                e.preventDefault();
                $('#ebook-form').attr('action', url + '?s=published').submit();
            });
        });
    </script>
@stop
