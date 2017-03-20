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
                        <label for="codproduto" class="control-label">#</label>
                        {!! Form::number('codproduto', null, ['class'=> 'form-control', 'id'=>'codproduto', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codproduto" class="control-label">Produto</label>
                        {!! Form::text('codproduto', null, ['class'=> 'form-control', 'id'=>'codproduto']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="produto" class="control-label">Produto</label>
                        {!! Form::text('produto', null, ['class'=> 'form-control', 'id'=>'produto']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="referencia" class="control-label">Referencia</label>
                        {!! Form::text('referencia', null, ['class'=> 'form-control', 'id'=>'referencia']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codunidademedida" class="control-label">Codunidademedida</label>
                        {!! Form::text('codunidademedida', null, ['class'=> 'form-control', 'id'=>'codunidademedida']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codsubgrupoproduto" class="control-label">Codsubgrupoproduto</label>
                        {!! Form::text('codsubgrupoproduto', null, ['class'=> 'form-control', 'id'=>'codsubgrupoproduto']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codmarca" class="control-label">Codmarca</label>
                        {!! Form::text('codmarca', null, ['class'=> 'form-control', 'id'=>'codmarca']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="preco" class="control-label">Preco</label>
                        {!! Form::text('preco', null, ['class'=> 'form-control', 'id'=>'preco']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="importado" class="control-label">Importado</label>
                        {!! Form::text('importado', null, ['class'=> 'form-control', 'id'=>'importado']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codtributacao" class="control-label">Codtributacao</label>
                        {!! Form::text('codtributacao', null, ['class'=> 'form-control', 'id'=>'codtributacao']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codtipoproduto" class="control-label">Codtipoproduto</label>
                        {!! Form::text('codtipoproduto', null, ['class'=> 'form-control', 'id'=>'codtipoproduto']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="site" class="control-label">Site</label>
                        {!! Form::text('site', null, ['class'=> 'form-control', 'id'=>'site']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="descricaosite" class="control-label">Descricaosite</label>
                        {!! Form::text('descricaosite', null, ['class'=> 'form-control', 'id'=>'descricaosite']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codncm" class="control-label">Codncm</label>
                        {!! Form::text('codncm', null, ['class'=> 'form-control', 'id'=>'codncm']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codcest" class="control-label">Codcest</label>
                        {!! Form::text('codcest', null, ['class'=> 'form-control', 'id'=>'codcest']) !!}
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
                        <label for="codopencart" class="control-label">Codopencart</label>
                        {!! Form::text('codopencart', null, ['class'=> 'form-control', 'id'=>'codopencart']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codopencartvariacao" class="control-label">Codopencartvariacao</label>
                        {!! Form::text('codopencartvariacao', null, ['class'=> 'form-control', 'id'=>'codopencartvariacao']) !!}
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Referencia', 'Produto', 'SubGrupo', 'Marca', 'UND', 'Preco' ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("produto/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('produto/datatable'), 'order' => $filtro['order'], 'filtros' => ['codproduto', 'codproduto', 'inativo', 'produto', 'referencia', 'codunidademedida', 'codsubgrupoproduto', 'codmarca', 'preco', 'importado', 'codtributacao', 'codtipoproduto', 'site', 'descricaosite', 'codncm', 'codcest', 'observacoes', 'codopencart', 'codopencartvariacao', ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
