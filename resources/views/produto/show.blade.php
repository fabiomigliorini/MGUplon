@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-6'>
        <div class='card'>
            <h4 class="card-header">Imagens</h4>
            <div class='card-block'>
                @include('produto.show-imagens')
            </div>
        </div>
    </div>
    <div class='col-md-6'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab-variacoes" role="tab">Detalhes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-estoque" role="tab">Estoque</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-site" role="tab">Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-fiscal" role="tab">NCM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-negocio" role="tab">Negócios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#nfes" role="tab">NFE's</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#observacoes" role="tab">Observações</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-variacoes" role="tabpanel">
                        <div class='row'>
                            <div class='col-md-7 col-sm-12'>
                                <ol class="breadcrumb" style="margin: 0 0 15px 0">
                                    {!! 
                                        titulo(
                                            NULL, 
                                            [
                                                url("secao-produto/{$model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}") => $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto,
                                                url("familia-produto/{$model->SubGrupoProduto->GrupoProduto->codfamiliaproduto}") => $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto,
                                                url("grupo-produto/{$model->SubGrupoProduto->codgrupoproduto}") => $model->SubGrupoProduto->GrupoProduto->grupoproduto,
                                                url("sub-grupo-produto/{$model->codsubgrupoproduto}") => $model->SubGrupoProduto->subgrupoproduto,
                                                url("marca/{$model->codmarca}") => $model->Marca->marca,
                                                $model->referencia,
                                            ], 
                                            NULL) 
                                    !!}
                                </ol>
                                <ol class="breadcrumb">
                                    <?php 
                                    $arr = [            
                                        url("tipo-produto/{$model->codtipoproduto}") => $model->TipoProduto->tipoproduto,
                                        url("ncm/{$model->codncm}") => formataNcm($model->Ncm->ncm),
                                        url("tributacao/{$model->codtributacao}") => $model->Tributacao->tributacao,
                                    ];

                                    if (!empty($model->codcest))
                                        $arr[url("cest/{$model->codcest}")] = formataCest($model->Cest->cest);

                                    $arr[] = ($model->importado)?'Importado':'Nacional';

                                    ?>
                                    {!! 
                                        titulo(NULL, $arr, NULL) 
                                    !!}
                                </ol>
                                <br>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                @include('produto.show-embalagens')
                            </div>
                        </div>

                        <a href="<?php echo url("produto-variacao/create?codproduto={$model->codproduto}");?>">Nova Variação <span class="fa fa-plus"></span></a>
                        &nbsp;|&nbsp;
                        <a href="<?php echo url("produto/$model->codproduto/transferir-variacao");?>">Transferir Variação <span class="fa fa-exchange"></span></a>
                        &nbsp;|&nbsp;
                        <a href="<?php echo url("produto-barra/create?codproduto={$model->codproduto}");?>">Novo Código de Barras <span class="fa fa-plus"></span></a>

                        <br>
                        @include('produto.show-variacoes')
                        
                    </div>
                    <div class="tab-pane" id="tab-estoque" role="tabpanel">
                        <div id="div-estoque">
                            <b>Aguarde...</b>
                        </div>                        
                    </div>
                    <div class="tab-pane" id="tab-site" role="tabpanel">
                        <p>
                            <botton class="btn btn-secondary" id="integracao-open-cart"><i class="fa fa-shopping-cart"></i> 
                                Sincronizar &nbsp;&nbsp;
                                <img width="20px" id="sincronizar" src="{{ URL::asset('public/img/carregando.gif') }}">
                            </botton>

                        </p>
                        <br>
                        <strong>Divulgado no Site: {{ ($model->site)?'Sim':'Não' }}</strong>
                        <hr>
                        {!! nl2br($model->descricaosite) !!}
                        
                    </div>
                    <div class="tab-pane" id="tab-fiscal" role="tabpanel">
                        @include('produto.show-ncm')
                    </div>
                    <div class="tab-pane" id="tab-negocio" role="tabpanel">
                        <!-- FILTRO NEGOCIO -->
                        <div class="collapse" id="collapseNegocios">
                            <div class='well well-sm'>
                                {!! Form::model(Request::session()->get('MGLara.Http.Controllers.NegocioProdutoBarraController.filtros'), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'class' => 'form-horizontal', 'method' => 'GET', 'id' => 'produto-negocio-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('negocio_lancamento_de', 'De') !!}
                                            {!! Form::date('negocio_lancamento_de', null, ['class' => 'form-control', 'id' => 'negocio_lancamento_de', 'placeholder' => 'De']) !!}
                                        </div> 
                                        <div class="form-group">
                                            {!! Form::label('negocio_lancamento_ate', 'Até') !!}
                                            {!! Form::date('negocio_lancamento_ate', null, ['class' => 'form-control', 'id' => 'negocio_lancamento_ate', 'placeholder' => 'Até']) !!}
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('negocio_codfilial', 'Filial') !!}
                                            {!! Form::select2Filial('negocio_codfilial', null, ['style'=>'width:100%', 'id'=>'negocio_codfilial']) !!}
                                        </div>                                    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('negocio_codnaturezaoperacao', 'Natureza de Operação') !!}
                                            {!! Form::select2NaturezaOperacao('negocio_codnaturezaoperacao', null, ['style'=>'width:100%', 'id' => 'negocio_codnaturezaoperacao']) !!}
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('negocio_codprodutovariacao', 'Variação') !!}
                                            {!! Form::select2ProdutoVariacao('negocio_codprodutovariacao', null, ['style'=>'width:100%', 'id' => 'negocio_codprodutovariacao', 'codproduto'=>'negocio_codproduto']) !!}
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('negocio_codpessoa', 'Pessoa') !!}
                                            {!! Form::select2Pessoa('negocio_codpessoa', null, ['class' => 'form-control', 'id'=>'negocio_codpessoa', 'style'=>'width:100%', 'placeholder' => 'Pessoa', 'ativo' => 9]) !!}
                                        </div>                            
                                    </div>
                                </div>
                                {!! Form::hidden('negocio_codproduto', $model->codproduto, ['id'=>'negocio_codproduto']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <br>
                        <div id="div-negocioss" class="table-responsive">
                            <a class='btn btn-secondary btn-sm' href='#collapseNegocios' data-toggle='collapse' aria-expanded='false' aria-controls='collapseNegocios'><i class='fa fa-search'></i></a>
                            @include('layouts.includes.datatable.html', ['id' => 'negocios', 'colunas' => ['URL', 'Inativo Desde', 'Negócio', 'Lançamento', 'Pessoa', 'Operação', 'Filial', 'Variação', 'Barras', 'Valor', 'UND', 'QTD']])
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="tab-pane" id="nfes" role="tabpanel">NFE's</div>
                    <div class="tab-pane" id="observacoes" role="tabpanel">
                        {!! $model->observacoes !!}
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("produto/$model->codproduto/edit") }}"><i class="fa fa-pencil"></i></a>
    <a class="btn btn-secondary btn-sm" href="{{ url("produto/create/?duplicar={$model->codproduto}") }}"><i class="fa fa-copy"></i></a>    
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("produto/$model->codproduto/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("produto/$model->codproduto/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("produto/$model->codproduto") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->codproduto }}'?" data-after="location.replace('{{ url('produto') }}');"><i class="fa fa-trash"></i></a>
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<style type="text/css">
    .nav-tabs .nav-link {
        padding: 0.5em 0.7em !important;
    }
    .nav-tabs {
        margin-bottom: 1rem !important;
    }
        
</style>
<link href="{{ URL::asset('public/assets/css/bootstrap-alpha6-carousel.css') }}" rel="stylesheet" type="text/css"/>
@include('layouts.includes.datatable.assets')
<script type="text/javascript">
function mostraListagemNegocios()
{
    var datable_negocios = $('#negocios').DataTable({
        dom: 'rtpi',
        pageLength: 100,
        language: {
            url: "http://localhost/MGUplon/public/assets/plugins/datatables/Portuguese-Brasil.lang"
        },
        processing: true,
        serverSide: true,
        order: 
        [
                ],
        ajax: {
            url: 'http://localhost/MGUplon/negocio-produto-barra/datatable',
            data: function ( d ) {
                d.filtros = new Object;
                    d.filtros.negocio_lancamento_de         = $('#negocio_lancamento_de').val();
                    d.filtros.negocio_lancamento_ate        = $('#negocio_lancamento_ate').val();
                    d.filtros.negocio_codfilial             = $('#negocio_codfilial').val();
                    d.filtros.negocio_codnaturezaoperacao   = $('#negocio_codnaturezaoperacao').val();
                    d.filtros.negocio_codprodutovariacao    = $('#negocio_codprodutovariacao').val();
                    d.filtros.negocio_codpessoa             = $('#negocio_codpessoa').val();
                    d.filtros.negocio_codproduto            = $('#negocio_codproduto').val();
                }
        },
        lengthChange: false,
        columnDefs: [
                        {
                targets: [0, 1],
                visible: false,
            },
            {
                render: function ( data, type, row ) {
                    return '<a href="' + row[0] + '">' + data +'</a>';
                },
                targets: 2
            }
        ],
        initComplete: function(settings, json) {
            datable_negocios.buttons().container().appendTo('#negocios_wrapper .col-md-12:eq(0)');
            $('#negocios_paginate, #negocios_info').addClass('col-md-12');
            $('ul.pagination').addClass('pull-left');
        },
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (aData[1] != null) {
                $(nRow).addClass('table-danger');
            }
        }
    });

    $('#negocio_lancamento_de').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_lancamento_ate').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_codfilial').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_codnaturezaoperacao').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_codprodutovariacao').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_codpessoa').change(function() {
        datable_negocios.ajax.reload();
    });
}

/*function mostraListagemNotasFiscais()
{
    console.log('mostraListagemNotasFiscais');
    
    //Serializa FORM
    var frmValues = $("#produto-notasfiscais-search").serialize();
    
    // Busca Listagem
    $.ajax({
        type: 'GET',
        url: baseUrl + '/produto/' + {{ $model->codproduto }},
        data: frmValues
    })
    .done(function (data) {
        
        $('#div-notasfiscais').html($(data).html()); 
        
        $('#div-notasfiscais-listagem').infinitescroll({
            loading : {
                finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
                msgText: "<div class='center'>Carregando mais itens...</div>",
                img: baseUrl + '/public/img/ajax-loader.gif'
            },
            navSelector : "#div-notasfiscais .pagination",
            nextSelector : "#div-notasfiscais .pagination li.active + li a",
            itemSelector : "#div-notasfiscais-listagem div.list-group-item"
        });
        
    })
    .fail(function (e) {
        console.log('Erro no filtro');
        console.log(e);
    });
}*/

$(document).ready(function() {
    ////////// LISTAGEM DE NEGOCIOS /////////
    //
    // Listagem de Negocios -- Troca ABA
    var listagemNegocioAberta = false;
    $('a[href="#tab-negocio"]').on('shown.bs.tab', function (e) {
        if (!listagemNegocioAberta)
            mostraListagemNegocios();
        listagemNegocioAberta = true;
    });
    
/*
    // Listagem de Negocios -- Alteração Formulário
    $("#produto-negocio-search").on("change", function (event) {
        mostraListagemNegocios();
        event.preventDefault(); 
    });
    /////////////////////////////////////////
*/
    
/*
    ////////// LISTAGEM DE NOTAS FISCAIS /////////
    //
    // Listagem de Notas Fiscais -- Troca ABA
    var listagemNotasFiscaisAberta = false;
    $('a[href="#tab-notasfiscais"]').on('shown.bs.tab', function (e) {
        if (!listagemNotasFiscaisAberta)
            mostraListagemNotasFiscais();
        listagemNotasFiscaisAberta = true;
    });
    
    // Listagem de Negocios -- Alteração Formulário
    $("#produto-notasfiscais-search").on("change", function (event) {
        mostraListagemNotasFiscais();
        event.preventDefault(); 
    });
    /////////////////////////////////////////
*/
    var listagemEstoqueAberta = false;
    $('a[href="#tab-estoque"]').on('shown.bs.tab', function (e) {
        recarregaDiv('div-estoque');
        listagemEstoqueAberta = true;
    });
    
    $('#sincronizar').hide();
    $('#integracao-open-cart').click(function (e) {
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja sincronizar esse produto", function(result) {
            if (result) {
                $.ajax({
                    type: 'GET',
                    url: baseUrl + '/produto/sincroniza-produto-open-cart',
                    data: {
                        id:{{ $model->codproduto }}
                    },
                    beforeSend: function( xhr ) {
                        $('#sincronizar').show(function() {
                            $('#integracao-open-cart').attr('disabled','disabled');
                        });
                    }
                })
                .done(function (data) {
                    $('#sincronizar').hide(function() {
                        $('#integracao-open-cart').removeAttr('disabled');
                    });
                    if(data.resultado === true) {
                        var mensagem = '<strong class="text-success">'+data.mensagem+'</strong>';
                        bootbox.alert(mensagem);
                        console.log(data.resultado);
                    } else {
                        var mensagem = '<strong class="text-danger">'+data.mensagem+'</strong>';
                        mensagem += '<hr><pre>';
                        mensagem += JSON.stringify(data.exception, undefined, 2);
                        mensagem += '</pre>';                        
                        bootbox.alert(mensagem);
                        console.log(data.resultado);
                    }
                })
                .fail(function (data) {
                    console.log('erro no POST');
                });                 
            }
        }); 
    }); 
    
    /*    
    $('#codproduto').change(function (){
        window.location.href = '{{ url("produto/") }}' + $('#codproduto').val();
    });
    
    });
    */    
    
});

</script>

@endsection
@stop