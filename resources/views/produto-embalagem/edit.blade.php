@extends('layouts.default')
@section('content')
<div class='row'>
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">Alterar</h4>
            <div class="card-block">
                {!! Form::model($model, ['method' => 'PATCH', 'id' => 'form-principal', 'action' => ['ProdutoEmbalagemController@update', $model->codprodutoembalagem] ]) !!}
                    @include('errors.form_error')
                    @include('produto-embalagem.form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@stop