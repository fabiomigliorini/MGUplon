@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h4 class="card-header">
                Novo
            </h4>
            <div class="card-block">
                {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-principal', 'route' => 'vale-compra-modelo.store']) !!}
                    @include('errors.form_error')
                    @include('vale-compra-modelo.form')
                {!! Form::close() !!}   
            </div>
        </div>
    </div>
</div>
@stop