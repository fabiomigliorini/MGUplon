@extends('layouts.default')
@section('content')
<div class="col-xs-12">
    <div class="card">
        <h3 class="card-header">
            {{ $model->usuario }}
        </h3>
        <div class="card-block">
            {!! Form::model($model, ['method' => 'PATCH', 'id' => 'form-usuario', 'action' => ['UsuarioController@update', $model->codusuario] ]) !!}
                @include('errors.form_error')
                @include('usuario.form')
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop