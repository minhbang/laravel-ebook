@extends($layout)
@section('content')
    <div class="row">
        <div class="col-md-6">
            <table class="table table-hover table-striped table-bordered table-detail">
                <tr>
                    <td>ID</td>
                    <td><strong>{{ $ebook->id}}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.title') }}</td>
                    <td><strong>{{ $ebook->title }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.slug') }}</td>
                    <td><strong>{{ $ebook->slug }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.category_id') }}</td>
                    <td><strong>{{ $ebook->present()->category }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.featured_image') }}</td>
                    <td>{!! $ebook->present()->featured_image_lightbox !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.files') }}</td>
                    <td>{!! $ebook->present()->files($route_prefix. 'backend.ebook.preview') !!}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-hover table-striped table-bordered table-detail">
                <tr>
                    <td>{{ trans('ebook::common.status') }}</td>
                    <td>{!! $ebook->present()->status !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.featured') }}</td>
                    <td>{!! Html::yesNoLabel($ebook->featured, trans('common.yes'), trans('common.no')) !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.security_id') }}</td>
                    <td>{!! $ebook->present()->security !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.language_id') }}</td>
                    <td><strong>{{ $ebook->language_title }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.writer_id') }}</td>
                    <td><strong>{{ $ebook->present()->writer }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.pages') }}</td>
                    <td><strong>{{ $ebook->pages }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.pyear') }}</td>
                    <td><strong>{{ $ebook->pyear }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.publisher_id') }}</td>
                    <td><strong>{{ $ebook->publisher_title }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.pplace_id') }}</td>
                    <td><strong>{{ $ebook->pplace_title }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.user_id') }}</td>
                    <td><strong>{{ $ebook->user->username }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('common.created_at') }}</td>
                    <td>{!! $ebook->present()->createdAt !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('common.updated_at') }}</td>
                    <td>{!! $ebook->present()->updatedAt !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('ebook::common.hit') }}</td>
                    <td><code>{{$ebook->hit}}</code></td>
                </tr>
            </table>
        </div>
    </div>
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>{{ trans('ebook::common.summary') }}</td>
            <td class="white-bg">{!! $ebook->summary !!}</td>
        </tr>
    </table>
@stop