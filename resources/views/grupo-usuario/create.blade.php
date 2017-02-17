@extends('layouts.default')
@section('content')
<div class="col-sm-5 col-xs-12">
    <div class="card">
        <h3 class="card-header">
            Novo Grupo de Usuário
        </h3>
        <div class="card-block">
            {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-grupo-usuario', 'route' => 'grupo-usuario.store']) !!}
                @include('errors.form_error')
                @include('grupo-usuario.form')
            {!! Form::close() !!}   
        </div>
    </div>
</div>
@stop