@extends('layouts.default')
@section('content')
<div class="col-sm-5 col-xs-12">
    <div class="card">
        <h3 class="card-header">
            Nova Unidade de Medida
        </h3>
        <div class="card-block">
            {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-unidade-medida', 'route' => 'unidade-medida.store']) !!}
               @include('unidade-medida.form')
            {!! Form::close() !!}   
        </div>
    </div>
</div>
@stop