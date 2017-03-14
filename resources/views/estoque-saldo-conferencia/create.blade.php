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
                    {!! Form::label('fiscal', 'Físico') !!}
                    {!! Form::select2FisicoFiscal('fiscal', null, ['class'=> 'form-control', 'id'=>'fiscal', 'autofocus']) !!}
                </fieldset>
                <fieldset class="form-group">
                   {!! Form::submit('Localizar', array('class' => 'btn btn-primary')) !!}
                </fieldset>
                
                {!! Form::close() !!}   
            </div>
        </div>
    </div>
    <div class='col-md-8' id='div-saldos'>
      
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
        // Caso Erro
        error: function (XHR) {

            if(XHR.status === 403) {
               var title = 'Permissão Negada!';
            } else if(XHR.status === 404) {
               var title = 'Não Localizado!';
            } else {
               var title = 'Falha na execução!';
            }

            swal({
                title: title,
                text: XHR.status + ' ' + XHR.statusText,
                type: 'error',
            });
            
        }
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
    $("#observacoes").maxlength({alwaysShow: true});
});
</script>
@endsection