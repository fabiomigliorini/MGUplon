@extends('layouts.default')
@section('content')
<div class="card-box col-md-6">
{!! Form::model($model, ['method' => 'PATCH', 'id' => 'form-unidade-medida', 'action' => ['UnidadeMedidaController@update', $model->codunidademedida] ]) !!}
    @include('unidade-medida.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
</div>
@stop