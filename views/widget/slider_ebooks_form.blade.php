<?php /** @var \Minhbang\Layout\Widget $widget */ ?>
@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($data,['class' => 'form-modal form-row-full','url' => $url, 'method' => 'put']) !!}
    <div class="row">
        <div class="col-sm-9">
            <div class="flex-col-inner">
                <div class="form-group {{ $errors->has("category_id") ? ' has-error':'' }}">
                    {!! Form::label("category_id", $labels['category_id'], ['class' => "control-label"]) !!}
                    {!! Form::select('category_id', $widget->typeInstance()->getCategories(), null, ['prompt' =>trans( 'category::common.select_category' ), 'class' => 'form-control selectize-tree']) !!}
                    @if($errors->has('category_id'))
                        <p class="help-block">{{ $errors->first('category_id') }}</p>
                    @endif
                </div>
                @include('layout::widgets._common_fields')
            </div>
        </div>
        <div class="col-sm-3 gray-bg">
            <div class="form-group{{ $errors->has('limit') ? ' has-error':'' }}">
                {!! Form::label('label', $labels['limit'], ['class' => 'control-label']) !!}
                {!! Form::text('limit', null, ['class' => 'form-control']) !!}
                @if($errors->has('limit'))
                    <p class="help-block">{{ $errors->first('limit') }}</p>
                @endif
            </div>
            <div class="form-group{{ $errors->has('query_type') ? ' has-error':'' }}">
                {!! Form::label('label', $labels['query_type'], ['class' => 'control-label']) !!}
                {!! Form::select('query_type', trans('ebook::widget.slider_ebooks.query_types'), null, ['class' => 'form-control']) !!}
                @if($errors->has('query_type'))
                    <p class="help-block">{{ $errors->first('query_type') }}</p>
                @endif
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop