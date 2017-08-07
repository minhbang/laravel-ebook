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
                <div class="form-group{{ $errors->has('item_css') ? ' has-error':'' }}">
                    {!! Form::label('label', $labels['item_css'], ['class' => 'control-label']) !!}
                    {!! Form::text('item_css', null, ['class' => 'form-control']) !!}
                    <p class="help-block">{!! $errors->has('item_css') ? $errors->first('item_css'): trans('article::widget.latest_articles.item_css_hint')  !!}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3 gray-bg">
            <div class="form-group {{ $errors->has('show_link_category') ? ' has-error':'' }}">
                {!! Form::label('show_link_category',  $labels['show_link_category'], ['class'=> 'control-label']) !!}
                <br>
                {!! Form::checkbox('show_link_category', 1, null,['class'=>'switch', 'data-on-text'=>trans('common.yes'), 'data-off-text'=>trans('common.no')]) !!}
                @if($errors->has('show_link_category'))
                    <p class="help-block">{{ $errors->first('show_link_category') }}</p>
                @endif
            </div>
            <div class="form-group{{ $errors->has('limit') ? ' has-error':'' }}">
                {!! Form::label('label', $labels['limit'], ['class' => 'control-label']) !!}
                {!! Form::text('limit', null, ['class' => 'form-control']) !!}
                @if($errors->has('limit'))
                    <p class="help-block">{{ $errors->first('limit') }}</p>
                @endif
            </div>
            <div class="form-group{{ $errors->has('item_style') ? ' has-error':'' }}">
                {!! Form::label('label', $labels['item_style'], ['class' => 'control-label']) !!}
                {!! Form::select('item_style', trans('ebook::widget.latest_ebooks.item_styles'), null, ['class' => 'form-control']) !!}
                @if($errors->has('item_style'))
                    <p class="help-block">{{ $errors->first('item_style') }}</p>
                @endif
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop