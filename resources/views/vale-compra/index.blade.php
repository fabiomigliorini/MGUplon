@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro, ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codvalecompra" class="control-label">#</label>
                        {!! Form::number('codvalecompra', null, ['class'=> 'form-control', 'id'=>'codvalecompra', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codvalecompra" class="control-label">Vale Compras</label>
                        {!! Form::text('codvalecompra', null, ['class'=> 'form-control', 'id'=>'codvalecompra']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codvalecompramodelo" class="control-label">codvalecompramodelo</label>
                        {!! Form::text('codvalecompramodelo', null, ['class'=> 'form-control', 'id'=>'codvalecompramodelo']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codpessoafavorecido" class="control-label">codpessoafavorecido</label>
                        {!! Form::text('codpessoafavorecido', null, ['class'=> 'form-control', 'id'=>'codpessoafavorecido']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codpessoa" class="control-label">codpessoa</label>
                        {!! Form::text('codpessoa', null, ['class'=> 'form-control', 'id'=>'codpessoa']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="observacoes" class="control-label">observacoes</label>
                        {!! Form::text('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="aluno" class="control-label">aluno</label>
                        {!! Form::text('aluno', null, ['class'=> 'form-control', 'id'=>'aluno']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="turma" class="control-label">turma</label>
                        {!! Form::text('turma', null, ['class'=> 'form-control', 'id'=>'turma']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="totalprodutos" class="control-label">totalprodutos</label>
                        {!! Form::text('totalprodutos', null, ['class'=> 'form-control', 'id'=>'totalprodutos']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="desconto" class="control-label">desconto</label>
                        {!! Form::text('desconto', null, ['class'=> 'form-control', 'id'=>'desconto']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="total" class="control-label">total</label>
                        {!! Form::text('total', null, ['class'=> 'form-control', 'id'=>'total']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codtitulo" class="control-label">codtitulo</label>
                        {!! Form::text('codtitulo', null, ['class'=> 'form-control', 'id'=>'codtitulo']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codfilial" class="control-label">codfilial</label>
                        {!! Form::text('codfilial', null, ['class'=> 'form-control', 'id'=>'codfilial']) !!}
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Aluno', 'Turma', 'Total', 'Pessoa', 'Favorecido', 'Modelo', 'Data', 'Usuário']])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('vale-compra/datatable'), 'order' => 9, 'order_dir' => 'DESC', 'filtros' => ['codvalecompra', 'codvalecompra', 'inativo', 'codvalecompramodelo', 'codpessoafavorecido', 'codpessoa', 'observacoes', 'aluno', 'turma', 'totalprodutos', 'desconto', 'total', 'codtitulo', 'codfilial', ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
