@extends('layouts.default')
@section('content')
<div class="card-box col-md-6">
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-unidade-medida', 'route' => 'unidade-medida.store']) !!}
    @include('unidade-medida.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
</div>
@stop