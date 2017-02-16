@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Usuários', null) !!}
    <li class='active'>
        <small>
            <a title="Novo Usuário" href="{{ url('usuario/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>   
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
          {!! Form::model(Request::session()->get('usuario.index'), [
            'route' => 'usuario.index', 
            'method' => 'GET', 
            'class' => 'form-horizontal',
            'id' => 'usuario-search',
            'role' => 'search'
          ])!!}
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('codusuario', '#', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-5">{!! Form::text('codusuario', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('usuario', 'Usuário', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('usuario', null, ['class' => 'form-control', 'placeholder' => 'Usuário']) !!}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('codpessoa', 'Pessoa', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'id'=>'codpessoa', 'placeholder' => 'Pessoa', 'ativo' => 9]) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codfilial', 'Filial', ['class' => 'col-sm-2 control-label']) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
            </div>              
            <div class="form-group">
                <div class="col-md-offset-3 col-md-10">              
                    <button type="submit" class="btn btn-default">Buscar</button>
                </div>
            </div>
        </div>  
        <div class="clearfix"></div>
    {!! Form::close() !!}
</div>
</div>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
  </div>
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#usuario-search").serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/usuario',
        data: frmValues
    })
    .done(function (data) {
        $('#items').html(jQuery(data).find('#items').html()); 
    })
    .fail(function () {
        console.log('Erro no filtro');
    });

    $('#items').infinitescroll('update', {
        state: {
            currPage: 1,
            isDestroyed: false,
            isDone: false             
        },
        path: ['?page=', '&'+frmValues]
    });
}

function scroll()
{
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: baseUrl + '/public/img/ajax-loader.gif'
    };

    $('#items').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#items div.list-group-item",
    });    
}
$(document).ready(function() {
    scroll();
    $("#usuario-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });        
});
</script>
@endsection
@stop

