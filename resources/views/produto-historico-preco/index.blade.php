@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro, ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codprodutohistoricopreco" class="control-label">#</label>
                        {!! Form::number('codprodutohistoricopreco', null, ['class'=> 'form-control', 'id'=>'codprodutohistoricopreco', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codproduto" class="control-label">Produto</label>
                        {!! Form::text('codproduto', null, ['class'=> 'form-control', 'id'=>'codproduto']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="referencia" class="control-label">Referência</label>
                        {!! Form::text('referencia', null, ['class'=> 'form-control', 'id'=>'referencia']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codmarca" class="control-label">Marca</label>
                        {!! Form::text('codmarca', null, ['class'=> 'form-control', 'id'=>'codmarca']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="precoantigo" class="control-label">Precoantigo</label>
                        {!! Form::text('precoantigo', null, ['class'=> 'form-control', 'id'=>'precoantigo']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codusuario" class="control-label">Usuário</label>
                        {!! Form::text('codusuario', null, ['class'=> 'form-control', 'id'=>'codusuario']) !!}
                    </div>
                </div>
                <div class="col-md-2">
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Produto', 'Embalagem', 'Ref', 'Marca', 'Preço','Antigo', 'Novo', 'Usuário', 'Data' ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('produto-historico-preco/datatable'), 'order' => 11, 'order_dir' => 'ASC', 'filtros' => ['codprodutohistoricopreco', 'codprodutohistoricopreco', 'inativo', 'codproduto', 'codprodutoembalagem', 'precoantigo', 'preconovo', ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
