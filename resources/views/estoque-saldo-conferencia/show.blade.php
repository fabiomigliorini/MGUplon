@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-5'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ formataCodigo($model->codestoquesaldoconferencia) }}</td> 
                    </tr>
                    <tr> 
                      <th>Produto</th> 
                      <td>
                        <a href='{{ url('produto', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto) }}'>
                        {{ $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto }}
                        </a>
                      </td> 
                    </tr>
                    <tr> 
                      <th>Variação</th> 
                      <td>{{ $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao or '{ Sem Variação }' }}</td> 
                    </tr>
                    <tr> 
                      <th>Local</th> 
                      <td>
                        <a href='{{ url('estoque-local', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal) }}'>
                        {{ $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->estoquelocal }}
                        </a>
                      </td> 
                    </tr>
                    <tr> 
                      <th>Quantidade</th> 
                      <td>
                        <span class='text-success'>
                        {{ formataNumero($model->quantidadeinformada, 3) }}
                        </span>
                        <s class='text-muted'>
                        {{ formataNumero($model->quantidadesistema, 3) }}
                        </s>
                      </td> 
                    </tr>
                    <tr> 
                      <th>Custo</th> 
                      <td>
                        <span class='text-success'>
                        {{ formataNumero($model->customedioinformado, 6) }}
                        </span>
                        <s class='text-muted'>
                        {{ formataNumero($model->customediosistema, 6) }}
                        </s>
                      </td> 
                    </tr>
                    <tr> 
                      <th>Data</th> 
                      <td>{{ formataData($model->data, 'L') }}</td> 
                    </tr>
                    <tr> 
                      <th>Observacoes</th> 
                      <td>{!! nl2br($model->observacoes) !!}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
    <div class='col-md-3'>
        <div class='card'>
            <h4 class="card-header">Movimento Gerado</h4>
            <div class='card-block'>
              @foreach ($model->EstoqueMovimentoS as $mov)
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td colspan='3'>{{ formataCodigo($mov->codestoquemovimento) }}</td> 
                    </tr>
                    <tr> 
                      <th>Mês</th> 
                      <td colspan='3'>
                        <a href='{{ url('estoque-mes', $mov->codestoquemes) }}'>
                        {{ $mov->EstoqueMes->mes->format('m/Y') }}
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <th colspan='2'>
                        Entrada
                      </th>
                      <th colspan='2'>
                        Saída
                      </th>
                    </tr>
                    <tr> 
                      <td>
                        <div class='pull-right'>
                        {{ formataNumero($mov->entradaquantidade, 3) }}
                        </div>
                      </td>
                      <td>
                        <div class='pull-right'>
                        {{ formataNumero($mov->entradavalor, 2) }}
                        </div>
                      </td>
                      <td>
                        <div class='pull-right'>
                        {{ formataNumero($mov->saidaquantidade, 3) }}
                        </div>
                      </td>
                      <td>
                        <div class='pull-right'>
                        {{ formataNumero($mov->saidavalor, 2) }}
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class='clearfix'></div>
              @endforeach
            </div>
        </div>
    </div>
</div>

@section('buttons')

    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("estoque-saldo-conferencia/$model->codestoquesaldoconferencia/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codestoquesaldoconferencia }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("estoque-saldo-conferencia/$model->codestoquesaldoconferencia/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codestoquesaldoconferencia }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')

@endsection
@stop