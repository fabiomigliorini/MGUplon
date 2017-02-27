@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">
                Novo
            </h4>
            <div class="card-block">
                {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-principal', 'route' => 'estado-civil.store']) !!}
                    @include('errors.form_error')
                    @include('estado-civil.form')
                {!! Form::close() !!}   
            </div>
        </div>
    </div>
</div>
@stop