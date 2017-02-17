@extends('layouts.default')
@section('content')
<div class="col-sm-5 col-xs-12">
    <div class="card">
        <h3 class="card-header">
            {{ $model->grupousuario }}
        </h3>
        <div class="card-block">
            {!! Form::model($model, ['method' => 'PATCH', 'id' => 'form-grupo-usuario', 'action' => ['GrupoUsuarioController@update', $model->codgrupousuario] ]) !!}
                @include('errors.form_error')
                @include('grupo-usuario.form')
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop