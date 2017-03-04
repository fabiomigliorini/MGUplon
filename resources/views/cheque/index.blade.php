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
                        <label for="codcheque" class="control-label">#</label>
                        {!! Form::number('codcheque', null, ['class'=> 'form-control', 'id'=>'codcheque', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codcheque" class="control-label">Cheque</label>
                        {!! Form::text('codcheque', null, ['class'=> 'form-control', 'id'=>'codcheque']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="cmc7" class="control-label">Cmc7</label>
                        {!! Form::text('cmc7', null, ['class'=> 'form-control', 'id'=>'cmc7']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codbanco" class="control-label">Codbanco</label>
                        {!! Form::text('codbanco', null, ['class'=> 'form-control', 'id'=>'codbanco']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="agencia" class="control-label">Agencia</label>
                        {!! Form::text('agencia', null, ['class'=> 'form-control', 'id'=>'agencia']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="contacorrente" class="control-label">Contacorrente</label>
                        {!! Form::text('contacorrente', null, ['class'=> 'form-control', 'id'=>'contacorrente']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="emitente" class="control-label">Emitente</label>
                        {!! Form::text('emitente', null, ['class'=> 'form-control', 'id'=>'emitente']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="numero" class="control-label">Numero</label>
                        {!! Form::text('numero', null, ['class'=> 'form-control', 'id'=>'numero']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="emissao" class="control-label">Emissao</label>
                        {!! Form::text('emissao', null, ['class'=> 'form-control', 'id'=>'emissao']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="vencimento" class="control-label">Vencimento</label>
                        {!! Form::text('vencimento', null, ['class'=> 'form-control', 'id'=>'vencimento']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="repasse" class="control-label">Repasse</label>
                        {!! Form::text('repasse', null, ['class'=> 'form-control', 'id'=>'repasse']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="destino" class="control-label">Destino</label>
                        {!! Form::text('destino', null, ['class'=> 'form-control', 'id'=>'destino']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="devolucao" class="control-label">Devolucao</label>
                        {!! Form::text('devolucao', null, ['class'=> 'form-control', 'id'=>'devolucao']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="motivodevolucao" class="control-label">Motivodevolucao</label>
                        {!! Form::text('motivodevolucao', null, ['class'=> 'form-control', 'id'=>'motivodevolucao']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="observacao" class="control-label">Observacao</label>
                        {!! Form::text('observacao', null, ['class'=> 'form-control', 'id'=>'observacao']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="lancamento" class="control-label">Lancamento</label>
                        {!! Form::text('lancamento', null, ['class'=> 'form-control', 'id'=>'lancamento']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="cancelamento" class="control-label">Cancelamento</label>
                        {!! Form::text('cancelamento', null, ['class'=> 'form-control', 'id'=>'cancelamento']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="valor" class="control-label">Valor</label>
                        {!! Form::text('valor', null, ['class'=> 'form-control', 'id'=>'valor']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codpessoa" class="control-label">Codpessoa</label>
                        {!! Form::text('codpessoa', null, ['class'=> 'form-control', 'id'=>'codpessoa']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="indstatus" class="control-label">Indstatus</label>
                        {!! Form::text('indstatus', null, ['class'=> 'form-control', 'id'=>'indstatus']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codtitulo" class="control-label">Codtitulo</label>
                        {!! Form::text('codtitulo', null, ['class'=> 'form-control', 'id'=>'codtitulo']) !!}
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Cheque', 'Banco', 'Agencia', 'Conta Corrente', 'Numero', 'Pessoa',  'Emitente(s)', 'Valor', 'Emissão', 'Vencimento', 'Status' ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("cheque/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('cheque/datatable'), 'order' => 3, 'order_dir' => 'ASC', 'filtros' => ['codcheque', 'codcheque', 'inativo', 'cmc7', 'codbanco', 'agencia', 'contacorrente', 'emitente', 'numero', 'emissao', 'vencimento', 'repasse', 'destino', 'devolucao', 'motivodevolucao', 'observacao', 'lancamento', 'cancelamento', 'valor', 'codpessoa', 'indstatus', 'codtitulo', ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
