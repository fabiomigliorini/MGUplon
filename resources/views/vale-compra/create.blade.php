@extends('layouts.default')
@section('content')
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-vale-compra', 'route' => 'vale-compra.store']) !!}
    @include('errors.form_error')
    @include('vale-compra.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop