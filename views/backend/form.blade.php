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
                    <div class="form-group tour_step1{{ $errors->has("title") ? ' has-error':'' }}">
                        {!! Form::label("title", trans('ebook::common.title'), ['class' => "control-label"]) !!}
                        {!! Form::text("title", null, ['class' => 'has-slug form-control',
                        'data-slug_target' => "#title-slug"]) !!}
                        @if($errors->has("title"))
                            <p class="help-block">{{ $errors->first("title") }}</p>
                        @endif
                    </div>
                    <div class="form-group tour_step2{{ $errors->has("slug") ? ' has-error':'' }}">
                        {!! Form::label("slug", trans('ebook::common.slug'), ['class' => "control-label"]) !!}
                        {!! Form::text("slug", null, ['id'=>"title-slug", 'class' => 'form-control']) !!}
                        @if($errors->has("slug"))
                            <p class="help-block">{{ $errors->first("slug") }}</p>
                        @endif
                    </div>
                    <div class="form-group tour_step3{{ $errors->has("summary") ? ' has-error':'' }}">
                        {!! Form::label("summary", trans('ebook::common.summary'), ['class' => "control-label"]) !!}
                        {!! Form::textarea("summary", null, [
                            'class' => 'form-control wysiwyg',
                            'data-editor' => 'simple',
                            'data-height' => 500,
                            'data-attribute' => 'summary',
                            'data-resource' => 'ebook',
                            'data-id' => $ebook->id
                        ]) !!}

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
                            <div class="row">
                                <div class="col-lg-12 col-xs-6">
                                    <div class="form-group tour_step4{{ $errors->has('featured') ? ' has-error':'' }}">
                                        {!! Form::label('featured',  trans('ebook::common.featured_ebook'), ['class'=> 'control-label']) !!}
                                        <br>
                                        {!! Form::checkbox('featured', 1, null,['class'=>'switch', 'data-on-text'=>trans('common.yes'), 'data-off-text'=>trans('common.no')]) !!}
                                        @if($errors->has('featured'))
                                            <p class="help-block">{{ $errors->first('featured') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xs-6">
                                    <div class="form-group tour_step5{{ $errors->has('status') ? ' has-error':'' }}">
                                        {!! Form::label('status',  trans('ebook::common.status'), ['class'=> 'control-label']) !!}
                                        {!! Form::select('status', $selectize_statuses, null, ['prompt' =>'', 'id' => 'selectize-status', 'class' => 'form-control']) !!}
                                        @if($errors->has('status'))
                                            <p class="help-block">{{ $errors->first('status') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group tour_step6{{ $errors->has('category_id') ? ' has-error':'' }}">
                                {!! Form::label('category_id', trans('category::common.category'), ['class' => 'control-label']) !!}
                                {!! Form::select('category_id', $categories, null, ['prompt' =>'', 'class' => 'form-control selectize-tree']) !!}
                                @if($errors->has('category_id'))
                                    <p class="help-block">{{ $errors->first('category_id') }}</p>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group tour_step7{{ $errors->has('security_id') ? ' has-error':'' }}">
                                        {!! Form::label('security_id', trans('ebook::common.security_id'), ['class' => 'control-label']) !!}
                                        {!! Form::select('security_id', $securities, null, ['prompt'=>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('security_id'))
                                            <p class="help-block">{{ $errors->first('security_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group tour_step8{{ $errors->has('writer_id') ? ' has-error':'' }}">
                                        {!! Form::label('writer_id', trans('ebook::common.writer_id'), ['class' => 'control-label']) !!}
                                        {!! Form::select('writer_id', $writers, null, ['data-creatable'=>'on', 'prompt'=>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('writer_id'))
                                            <p class="help-block">{{ $errors->first('writer_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group tour_step12{{ $errors->has('pages') ? ' has-error':'' }}">
                                        {!! Form::label('pages', trans('ebook::common.pages'), ['class' =>
                                        'control-label'])
                                         !!}
                                        {!! Form::text('pages', null, ['class' => 'form-control']) !!}
                                        @if($errors->has('pages'))
                                            <p class="help-block">{{ $errors->first('pages') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group tour_step13{{ $errors->has('pyear') ? ' has-error':'' }}">
                                        {!! Form::label('pyear', trans('ebook::common.pyear'), ['class' => 'control-label']) !!}
                                        {!! Form::text('pyear', null, ['class' => 'form-control']) !!}
                                        @if($errors->has('pyear'))
                                            <p class="help-block">{{ $errors->first('pyear') }}</p>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group tour_step9{{ $errors->has('language_id') ? ' has-error':'' }}">
                                        {!! Form::label('language_id', trans('ebook::common.language_id'), ['class' => 'control-label']) !!}
                                        {!! Form::select('language_id', $languages, null, ['data-creatable'=>'on', 'prompt' =>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('language_id'))
                                            <p class="help-block">{{ $errors->first('language_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group tour_step10{{ $errors->has('publisher_id') ? ' has-error':'' }}">
                                        {!! Form::label('publisher_id', trans('ebook::common.publisher_id'), ['class' => 'control-label']) !!}
                                        {!! Form::select('publisher_id', $publishers, null, ['data-creatable'=>'on', 'prompt'=>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('publisher_id'))
                                            <p class="help-block">{{ $errors->first('publisher_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group tour_step11{{ $errors->has('pplace_id') ? ' has-error':'' }}">
                                        {!! Form::label('pplace_id', trans('ebook::common.pplace_id'), ['class' =>'control-label']) !!}
                                        {!! Form::select('pplace_id', $pplaces, null, ['data-creatable'=>'on', 'prompt'=>'', 'class' => 'form-control selectize']) !!}
                                        @if($errors->has('pplace_id'))
                                            <p class="help-block">{{ $errors->first('pplace_id') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-5">
                            <div class="form-group tour_step14 form-image{{ $errors->has('image') ? ' has-error':'' }}">
                                {!! Form::label('image', trans('ebook::common.featured_image'), ['class' =>'control-label']) !!}
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
    {!! Form::hidden('selectedFiles') !!}
    {!! Form::close() !!}

    @include('file::backend._upload_form', ['tmp' => 1])
    <div class="ibox ibox-table tour_step15">
        <div class="ibox-title">
            <h5>{!! trans('ebook::common.files') !!}</h5>
            <div class="buttons">
                {!! Html::linkButton('#', trans('common.add'), ['id'=>'add-file','type'=>'success', 'size'=>'xs', 'icon' => 'plus-sign']) !!}
            </div>
        </div>
        <div class="ibox-content">
            @if($errors->has('files'))
                <div class="text-danger text-center space-15">{{ $errors->first('files') }}</div>
            @endif
            <table id="files" class="table table-striped table-hover table-bordered table-files">
                <tbody>
                @foreach($files as $i => $file)
                    <tr data-file_id="{{$file['id']}}">
                        <td class="min-width text-right">{{$i + 1}}</td>
                        <td>{!! $file['title'] !!}</td>
                        <td class="min-width text-center"><a href="#" class="btn text-danger remove-file"><i class="fa fa-remove"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-content">
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success save"
                        style="margin-right: 15px;">{{ trans('common.save') }}</button>
                <a href="{{ route($route_prefix.'backend.ebook.index') }}" class="btn btn-white">{{ trans('common.cancel') }}</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#selectize-status').selectize_status();
        $('.wysiwyg').mbEditor({
            //upload image
            imageUploadURL: '{!! route('image.store') !!}',
            imageMaxSize: {{setting('system.max_image_size') * 1024 * 1024 }}, //bytes
            // load image
            imageManagerLoadURL: '{!! route('image.data') !!}',
            // custom options
            imageDeleteURL: '{!! route('image.delete') !!}'
        });
        var ebook_form = $('#ebook-form');
        $("button.save").click(function (e) {
            e.preventDefault();
            $('input[name=selectedFiles]', ebook_form).val(selectedFiles());
            ebook_form.submit();
        });

        var filesTable = $('#files tbody'),
            fileTpl = '<tr>' +
                '<td class="min-width text-right"></td>' +
                '<td></td>' +
                '<td class="min-width text-center"><a href="#" class="btn text-danger remove-file"><i class="fa fa-remove"></i></a></td>' +
                '</tr>'
        ;

        $(filesTable).on("click", "a.remove-file", function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });

        function selectedFiles() {
            var result = '';
            $('tr', filesTable).each(function (index, tr) {
                result += (result.length ? ',' : '') + $(tr).data('file_id');
            });
            return result;
        }

        function addFile(file) {
            var newFile = $(fileTpl);
            newFile.data('file_id', file.id);
            $('td:nth-child(2)', newFile).html(file.title);
            filesTable.append(newFile);
            updateFilesTable();
        }

        function updateFilesTable() {
            $('tr', filesTable).each(function (index, tr) {
                $('td:first', tr).html(index + 1);
            });
        }

        $('#form-file').ajaxFileUpload({
            url_store: '{{route('backend.file.store')}}',
            add_new_button: '#add-file',
            trans: {
                add_new: "{{trans('file::common.add_new')}}",
                replace: "{{trans('file::common.replace')}}",
                ajax_upload: "{{trans('file::error.ajax_upload')}}",
                unable_upload: "{{trans('file::error.unable_upload')}}"
            },
            success: function (file) {
                addFile(file);
            }
        });

        // Hướng dẫn sử dụng ---------------------
        var tour = new Tour({
            steps: [
                    @for ($i = 1; $i <= 15; $i++)
                {
                    element: ".tour_step{{$i}}",
                    title: "{{trans("ebook::tour.step{$i}.title")}}",
                    content: "{!!trans("ebook::tour.step{$i}.content")!!}",
                    placement: "top",
                    backdrop: true,
                    backdropContainer: '#app',
                },
                @endfor
            ],
            template: "<div class='popover'>" +
            "   <div class='arrow'></div>" +
            "   <h3 class='popover-title'></h3>" +
            "   <div class='popover-content'></div>" +
            "   <div class='popover-navigation'>" +
            "       <div class='btn-group'><button class='btn btn-white' data-role='prev'>« {{trans('common.previous')}}</button>" +
            "       <button class='btn btn-white' data-role='next'>{{trans('common.next')}} »</button></div>" +
            "       <button class='btn btn-success' data-role='end'>{{trans('common.end')}}</button>" +
            "   </div>" +
            "</div>",
        });
        // Initialize the tour
        tour.init();
        $('.startTour').click(function (e) {
            tour.restart();
            e.preventDefault();
        })
    });
</script>
@endpush
