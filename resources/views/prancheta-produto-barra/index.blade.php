@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codpranchetaprodutobarra" class="control-label">#</label>
                        {!! Form::number('codpranchetaprodutobarra', null, ['class'=> 'form-control', 'id'=>'codpranchetaprodutobarra', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codpranchetaprodutobarra" class="control-label">Prancheta Produto Barra</label>
                        {!! Form::text('codpranchetaprodutobarra', null, ['class'=> 'form-control', 'id'=>'codpranchetaprodutobarra']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="observacoes" class="control-label">Observacoes</label>
                        {!! Form::text('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codprancheta" class="control-label">Codprancheta</label>
                        {!! Form::text('codprancheta', null, ['class'=> 'form-control', 'id'=>'codprancheta']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codprodutobarra" class="control-label">Codprodutobarra</label>
                        {!! Form::text('codprodutobarra', null, ['class'=> 'form-control', 'id'=>'codprodutobarra']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="inativo" class="control-label">Ativos</label>
                        {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                    </div>
                </div>
                <div class="clearfix"></div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
  </div>
</div>

<div class='card'>
    <div class='card-block table-responsive'>
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Prancheta Produto Barra', 'Observacoes', 'Codprancheta', 'Codprodutobarra', ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("prancheta-produto-barra/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('prancheta-produto-barra/datatable'), 'order' => $filtro['order'], 'filtros' => ['codpranchetaprodutobarra', 'codpranchetaprodutobarra', 'inativo', 'observacoes', 'codprancheta', 'codprodutobarra', ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
