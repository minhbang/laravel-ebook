@extends($layout)
@section('content')
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
            <td>{{ trans('ebook::common.filename') }}</td>
            <td><code>{!! $ebook->present()->fileicon !!} {{ $ebook->filename }}</code></td>
        </tr>
        <tr>
            <td>{{ trans('ebook::common.filesize') }}</td>
            <td><strong>{!! $ebook->present()->filesize !!}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('ebook::common.uploader') }}</td>
            <td><strong>{{ $ebook->user->username }}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('common.created_at') }}</td>
            <td>{!! $ebook->present()->createdAt !!}</td>
        </tr>
        <tr>
            <td>{{ trans('ebook::common.status') }}</td>
            <td>{!! $ebook->present()->statusFormatted !!}</td>
        </tr>
        <tr>
            <td>{{ trans('ebook::common.summary') }}</td>
            <td>{!! $ebook->summary !!}</td>
        </tr>
    </table>
@stop