@extends('layouts.default')
@section('content')
<h1>Unidade de Medida | Nova</h1>
<p>
    <a href="{{ url("unidade-medida") }}">Listagem</a>
</p>

{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-unidade-medida', 'action' => ['UnidadeMedidaController@update', $model->codunidademedida] ]) !!}
    @include('unidade-medida.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop