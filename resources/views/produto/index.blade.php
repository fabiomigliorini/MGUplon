@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            {!! Form::label('codproduto', '#') !!}
                            {!! Form::number('codproduto', null, ['class' => 'form-control', 'placeholder' => '#']) !!}
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            {!! Form::label('barras', 'Barras') !!}
                            {!! Form::text('barras', null, ['class' => 'form-control', 'placeholder' => 'Barras']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('produto', 'Descrição') !!}
                    {!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Descrição']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('referencia', 'Referência') !!}
                    {!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referência']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('preco_de', 'Preço') !!}
                    <div>
                        {!! Form::number('preco_de', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_de', 'placeholder' => 'De', 'style'=>'width:120px; margin-right:10px', 'step'=>'0.01']) !!}
                        {!! Form::number('preco_ate', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_ate', 'placeholder' => 'Até', 'style'=>'width:120px;', 'step'=>'0.01']) !!}
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            
            <div class="col-md-4">
            <div class="row">
                <div class="form-group col-md-6">
                    {!! Form::label('codmarca', 'Marca') !!}
                    {!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('codsecaoproduto', 'Seção') !!}
                    {!! Form::select2SecaoProduto('codsecaoproduto', null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'placeholder' => 'Seção']) !!}
                </div>
            </div>
                <div class="form-group">
                    {!! Form::label('codfamiliaproduto', 'Família') !!}
                    {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto',  'ativo'=>'9']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('codgrupoproduto', 'Grupo') !!}
                    {!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto', 'ativo'=>'9']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('codsubgrupoproduto', 'SubGrupo') !!}
                    {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'placeholder' => 'Sub Grupo', 'codgrupoproduto'=>'codgrupoproduto', 'ativo'=>'9']) !!}
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('codtributacao', 'Tributação') !!}
                    {!! Form::select2Tributacao('codtributacao', null, ['placeholder'=>'Tributação',  'class'=> 'form-control', 'id' => 'codtributacao']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('codncm', 'NCM') !!}
                    {!! Form::select2Ncm('codncm', null, ['class' => 'form-control','id'=>'codncm', 'placeholder' => 'NCM']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('criacao_de', 'Criação') !!}
                    <div>
                        {!! Form::date('criacao_de', null, ['class' => 'form-control pull-left', 'id' => 'criacao_de', 'placeholder' => 'De', 'style'=>'width:160px; margin-right:10px']) !!}
                        {!! Form::date('criacao_ate', null, ['class' => 'form-control pull-left', 'id' => 'criacao_ate', 'placeholder' => 'Até', 'style'=>'width:160px;']) !!}
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    {!! Form::label('alteracao_de', 'Alteração') !!}
                    <div>
                        {!! Form::date('alteracao_de', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_de', 'placeholder' => 'De', 'style'=>'width:160px; margin-right:10px']) !!}
                        {!! Form::date('alteracao_ate', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_ate', 'placeholder' => 'Até', 'style'=>'width:160px;']) !!}
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('site', 'Site') !!}
                                {!! Form::select('site', ['' => '', 'true' => 'No Site', 'false' => 'Fora do Site'], null, ['id'=>'site', 'style'=>'width:100%;']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inativo" class="control-label">Ativos</label>
                            {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!--
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
                -->


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
