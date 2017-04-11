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
                        <div class="form-group col-md-12">
                            <label class="control-label">#</label>
                            {!! Form::number('codcheque', null, ['class' => 'form-control text-right', 'placeholder' => '#', 'step'=>1, 'min'=>1, 'id' => 'codcheque']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Banco</label>
                            {!! Form::select2Banco('codbanco', null, ['class'=> 'form-control', 'id' => 'codbanco']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Agência</label>
                            {!! Form::number('agencia', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '', 'id' => 'agencia']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Número</label>
                            {!! Form::number('numero', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '', 'id' => 'numero']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Pessoa</label>
                            {!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'placeholder' => 'Pessoa']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Emitente</label>
                            {!! Form::text('emitente', null, ['class' => 'form-control', 'placeholder' => 'Emitente', 'id' => 'emitente']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Valor de</label>
                            {!! Form::number('valor_de', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '', 'id' => 'valor_de']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Valor até</label>
                            {!! Form::number('valor_ate', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '', 'id' => 'valor_ate']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Status</label>
                            {!! Form::select2('indstatus',  $status, ['class'=> 'form-control', 'id'=>'indstatus']) !!}
                       </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Vencimento De</label>
                            {!! Form::date('vencimento_de', null, ['class'=> 'form-control', 'id' => 'vencimento_de']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Até</label>
                            {!! Form::date('vencimento_ate', null, ['class'=> 'form-control', 'id' => 'vencimento_ate']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inativo" class="control-label">Ativos</label>
                            {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                        </div>
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Banco', 'Agencia', 'Contacorrente', 'Numero', 'Pessoa', 'Emitentes', 'Valor', 'Data Emissão', 'Data Vencimento', 'Status' ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("cheque/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('cheque/datatable'), 'order' => $filtro['order'], 'filtros' => ['codcheque', 'inativo', 'codbanco', 'agencia', 'contacorrente', 'emitente', 'numero', 'valor_de', 'valor_ate', 'vencimento_de', 'vencimento_ate', 'indstatus'] ])

    <script type="text/javascript">
        $(document).ready(function () {
            //----- Valor
            var valor_de = $('input[name=valor_de]').val();
            if(valor_de.length > 0 ){
                $('input[name=valor_ate]').attr('min', valor_de);
            }

            var valor_ate = $('input[name=valor_ate]').val();
            if(valor_de.length > 0 ){
                $('input[name=valor_de]').attr('min', valor_ate);
            }

            $('input[name=valor_de]').on('change', function(e) {
                e.preventDefault();
                setValorMin();
            }).blur(function () {
                setValorMin();
            });

            $('input[name=valor_ate]').on('change', function(e) {
                e.preventDefault();
                setValorMax();
            }).blur(function () {
                setValorMax();
            });

            //----- Data

            var vencimento_de = $('input[name=vencimento_de]').val();
            if(vencimento_de.length > 0 ){
                $('input[name=vencimento_ate]').attr('min', vencimento_de);
            }
            $('input[name=vencimento_de]').on('change', function(e) {
                e.preventDefault();
                var valor = $(this).val();
                if(valor.length === 0 ) {
                    $('input[name=vencimento_ate]').empty();
                    $('input[name=vencimento_ate]').attr('min', '');
                } else {
                    $('input[name=vencimento_ate]').attr('min', valor);
                }

            });

            var vencimento_ate = $('input[name=vencimento_ate]').val();
            if(vencimento_ate.length > 0){
                $('input[name=vencimento_de]').attr('max', vencimento_ate);
            }
            $('input[name=vencimento_ate]').on('change', function(e) {
                e.preventDefault();
                var valor = $(this).val();
                if(valor.length === 0 ) {
                    $('input[name=vencimento_de]').empty();
                    $('input[name=vencimento_de]').attr('max', '');
                } else {
                    $('input[name=vencimento_de]').attr('max', valor);
                }
            });
        });

        function setValorMin() {
            var valor = $('input[name=valor_de]').val();
            if(valor.length === 0 ) {
                $('input[name=valor_ate]').empty();
                $('input[name=valor_ate]').attr('min', '');
            } else {
                $('input[name=valor_ate]').attr('min', valor);
            }
        };

        function setValorMax() {
            var valor_de = $('input[name=valor_de]').val();
            var preco_ate = $('input[name=valor_ate]').val();
            if(valor_de.length === 0 ) {
                $('input[name=valor_de]').attr('max', preco_ate);
            }
        };
    </script>

@endsection
@stop
