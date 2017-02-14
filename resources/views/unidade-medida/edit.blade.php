@extends('layouts.default')
@section('content')
<div class="col-sm-5 col-xs-12">
    <div class="card">
        <h3 class="card-header">
            {{ $model->unidademedida }}
        </h3>
        <div class="card-block">
            {!! Form::model($model, ['method' => 'PATCH', 'id' => 'form-unidade-medida', 'action' => ['UnidadeMedidaController@update', $model->codunidademedida] ]) !!}
                @include('unidade-medida.form')
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop