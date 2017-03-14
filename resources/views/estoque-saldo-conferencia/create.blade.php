@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">
                Pesquisa Produto
            </h4>
            <div class="card-block">
                {!! Form::model('filtro', ['form-horizontal', 'id' => 'form-filtro']) !!}
                <fieldset class="form-group">
                    {!! Form::label('codestoquelocal', 'Barras') !!}
                    {!! Form::select2EstoqueLocal('codestoquelocal', null, ['class'=> 'form-control', 'id'=>'codestoquelocal']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('barras', 'Barras') !!}
                    {!! Form::text('barras', null, ['class'=> 'form-control', 'id'=>'barras', 'autofocus']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('codproduto', 'Produto') !!}
                    {!! Form::select2Produto('codproduto', null, ['class'=> 'form-control', 'id'=>'codproduto', 'autofocus']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('codprodutovariacao', 'Variação') !!}
                    {!! Form::select2ProdutoVariacao('codprodutovariacao', null, ['class'=> 'form-control', 'id'=>'codprodutovariacao', 'codproduto'=>'codproduto', 'autofocus']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('fiscal', 'Fiscal') !!}
                    {!! Form::select2FisicoFiscal('fiscal', null, ['class'=> 'form-control', 'id'=>'fiscal', 'autofocus']) !!}
                </fieldset>
                <fieldset class="form-group">
                   {!! Form::submit('Localizar', array('class' => 'btn btn-primary')) !!}
                </fieldset>
                
                {!! Form::close() !!}   
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">
                Saldos
            </h4>
            <div class="card-block" id='div-saldos'>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">
                Saldo
            </h4>
            <div class="card-block">

                {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-principal', 'route' => 'estoque-saldo-conferencia.store']) !!}
                @include('errors.form_error')

                <fieldset class="form-group">
                    {!! Form::label('codestoquesaldo', 'Codestoquesaldo') !!}
                    {!! Form::number('codestoquesaldo', null, ['class'=> 'form-control', 'id'=>'codestoquesaldo', 'step'=>'1', 'min'=>'1', 'required'=>'required', 'autofocus']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('quantidadesistema', 'Quantidadesistema') !!}
                    {!! Form::number('quantidadesistema', null, ['class'=> 'form-control', 'id'=>'quantidadesistema', 'step'=>'0.001', 'min'=>'0.001']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('quantidadeinformada', 'Quantidadeinformada') !!}
                    {!! Form::number('quantidadeinformada', null, ['class'=> 'form-control', 'id'=>'quantidadeinformada', 'step'=>'0.001', 'min'=>'0.001', 'required'=>'required']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('customediosistema', 'Customediosistema') !!}
                    {!! Form::number('customediosistema', null, ['class'=> 'form-control', 'id'=>'customediosistema', 'step'=>'0.000001', 'min'=>'0.000001']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('customedioinformado', 'Customedioinformado') !!}
                    {!! Form::number('customedioinformado', null, ['class'=> 'form-control', 'id'=>'customedioinformado', 'step'=>'0.000001', 'min'=>'0.000001', 'required'=>'required']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('data', 'Data') !!}
                    {!! Form::datetimeLocal('data', null, ['class'=> 'form-control', 'id'=>'data', 'required'=>'required']) !!}
                </fieldset>
                <fieldset class="form-group">
                    {!! Form::label('observacoes', 'Observacoes') !!}
                    {!! Form::text('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'maxlength'=>'200']) !!}
                </fieldset>
                <fieldset class="form-group">
                   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
                </fieldset>
                
                {!! Form::close() !!}   
            </div>
        </div>
    </div>
</div>
@stop
@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script type="text/javascript">
function validaFiltro() {
    if ($('#codestoquelocal').val() == '') {
        swal({
            title: 'Selecione o Local!',
            type: 'error',
        }, function () {
            $('#codestoquelocal').select2('open');
        });
        return false;
    }        
    if ($('#barras').val() == '' && $('#codprodutovariacao').val() == '') {
        swal({
            title: 'Selecione o Produto!',
            type: 'error',
        }, function () {
            $('#codproduto').select2('open');
        });
        return false;
    }
    return true;
}

function carregaSaldos() {
    $.ajax({
        url: '{{ url('estoque-saldo-conferencia/saldos') }}',
        type: 'get',
        data: {
            'codestoquelocal': $('#codestoquelocal').val(),
            'barras': $('#barras').val(),
            'codprodutovariacao': $('#codprodutovariacao').val(),
            'fiscal': $('#fiscal').val(),
        },
        dataType: 'html',
        success: function(data) {
            $('#div-saldos').html(data);
        },
    });    
}


function limpaFiltro() {
    $('#codproduto').val(null).trigger('change.select2');
    $('#codprodutovariacao').val(null).trigger('change.select2');
    $('#barras').val(null);
}
$(document).ready(function() {
    
    $('#form-filtro').on("submit", function(e) {
        e.preventDefault();
        if (!validaFiltro()) {
            return;
        }
        carregaSaldos();
    });
    $('#form-principal').on("submit", function(e) {
        e.preventDefault();
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja salvar?",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            currentForm.submit();
          }
        });
    });
    $("#observacoes").Setcase();
    $("#observacoes").maxlength({alwaysShow: true});
});
</script>
@endsection