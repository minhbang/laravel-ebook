@extends($layout)
@section('content')
    <div class="ibox ibox-table">
        <div class="ibox-title">
            <h5>{!! trans('ebook::common.manage_title') !!}</h5>
            <div class="buttons">
                {!! Html::linkButton('#', trans('common.filter'), ['class'=>'advanced_filter_collapse','type'=>'info', 'size'=>'xs', 'icon' => 'filter']) !!}
                {!! Html::linkButton('#', trans('common.all'), ['class'=>'advanced_filter_clear', 'type'=>'warning', 'size'=>'xs', 'icon' => 'list']) !!}
                {!! Html::linkButton(route($route_prefix.'backend.ebook.create'),trans('common.create'),['type'=>'success', 'size'=>'xs', 'icon' => 'plus-sign']) !!}
            </div>
        </div>
        <div class="ibox-content">
            <div class="bg-warning dataTables_advanced_filter hidden">
                <form class="form-horizontal" role="form">
                    {!! Form::hidden('filter_form', 1) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('filter_created_at', trans('common.created_at'), ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::daterange('filter_created_at', [], ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('filter_updated_at', trans('common.updated_at'), ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::daterange('filter_updated_at', [], ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {!! $html->table(['id' => 'ebook-manage']) !!}
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    window.datatableDrawCallback = function (dataTableApi) {
        dataTableApi.$('a.quick-update').quickUpdate({
            'url': '{{ route($route_prefix.'backend.ebook.quick_update', ['ebook' => '__ID__']) }}',
            'container': '#ebook-manage',
            'dataTableApi': dataTableApi
        });
        dataTableApi.$('select.select-btngroup').select_btngroup({'dataTableApi': dataTableApi});
        window.Holder.run({images: '#ebook-manage img'});
    };
    window.settings.mbDatatables = {
        trans: {
            name: '{{trans('ebook::common.ebook')}}'
        }
    }
</script>
{!! $html->scripts() !!}
@endpush