@extends('layouts.default')
@section('content')
<div class="collapse in" id="collapsePesquisa">
    <div class="card">
      <h4 class="card-header">Pesquisa</h4>
      <div class="card-block">
          <div class="card-text">
              {!! Form::model($filtro, ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                  <div class="col-md-4">
                      <div class="form-group">
                          {!! Form::label('codusuario', 'Usuário') !!}
                          {!! Form::select2Usuario('codusuario', null, ['class' => 'form-control', 'required'=>true]) !!}
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="row">
                            <div class="form-group col-md-6">
                                {!! Form::label('datainicial', 'De:') !!}
                                {!! Form::datetimeLocal('datainicial', null, ['class' => 'form-control text-center', 'required'=>true, 'placeholder' => 'De']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('datafinal', 'Até:') !!}
                                {!! Form::datetimeLocal('datafinal', null, ['class' => 'form-control text-center', 'required'=>true, 'placeholder' => 'Até']) !!}
                            </div>
                        </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('ativo', 'Ativo') !!}
                          {!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}
                      </div>
                  </div>
                  <div class="clearfix"></div>
              {!! Form::close() !!}
              <div class='clearfix'></div>
          </div>
      </div>
    </div>
</div>

<div class="card">
  <h4 class="card-header">Totais</h4>
    <div class="card-block">
        <table class='table table-bordered table-hover table-striped table-condensed' id="items">
          <thead>
            <tr>
              <th>
                Status
              </th>
              <th>
                Item
              </th>
              <th>
                Entrada
              </th>
              <th>
                Saída
              </th>
              <th>
                Prazo
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($dados['negocios'] as $neg)
              <?php
                  switch ($neg->codnegociostatus) {
                      case 1: // Aerto
                          $class='info';
                          break;

                      case 3: // Cancelado
                          $class='danger';
                          break;

                      default:
                          $class='';
                          break;
                  }
              ?>
              <tr class='{{ $class }}'>
                <td>
                  Negócio {{ $neg->negociostatus }}
                </td>
                <td>
                  {{ $neg->naturezaoperacao }}
                  <span class="badge badge-pill pull-right badge-primary">{{ formataNumero($neg->quantidade, 0) }}</span>

                </td>
                <td class='text-right'>
                  @if ($neg->codoperacao == 2)
                    {{ formataNumero($neg->avista) }}
                  @endif
                </td>
                <td class='text-right'>
                  @if ($neg->codoperacao == 1)
                    {{ formataNumero($neg->avista) }}
                  @endif
                </td>
                <td class='text-right'>
                  {{ formataNumero($neg->aprazo) }}
                </td>
              </tr>
            @endforeach

            @foreach ($dados['vales'] as $vale)
              <?php
                  switch ($vale->status) {
                      case 'Inativo':
                          $class='danger';
                          break;

                      default:
                          $class='';
                          break;
                  }
              ?>
              <tr class='{{ $class }}'>
                <td colspan='2'>
                  Vale Compras {{ $vale->status }}
                    <span class="badge badge-pill pull-right badge-primary">{{ formataNumero($vale->quantidade, 0) }}</span>
                </td>
                <td class='text-right'>
                  {{ formataNumero($vale->avista) }}
                </td>
                <td class='text-right'>
                </td>
                <td class='text-right'>
                  {{ formataNumero($vale->aprazo) }}
                </td>
              </tr>
            @endforeach

            @foreach ($dados['liquidacoes'] as $liq)
              <?php
                  switch ($liq->status) {
                      case 'Estornada':
                          $class='danger';
                          break;

                      default:
                          $class='';
                          break;
                  }
              ?>
              <tr class='{{ $class }}'>
                <td colspan='2'>
                  Liquidação {{ $liq->status }}
                  <span class="badge badge-pill pull-right badge-primary">{{ formataNumero($liq->quantidade, 0) }}</span>
                </td>
                <td class='text-right'>
                  {{ formataNumero($liq->credito) }}
                </td>
                <td class='text-right'>
                  {{ formataNumero($liq->debito) }}
                </td>
                <td class='text-right'>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</div>


@section('buttons')

    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#caixa-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/caixa',
        data: frmValues,
        dataType: 'html'
    })
    .done(function (data) {
        console.log('Entrou2');
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
    $("#caixa-search").on("change", function (event) {
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