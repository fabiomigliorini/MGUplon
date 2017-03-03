@extends('layouts.default')
@section('content')

<div class='row'>
  <div class='col-md-8'>
    <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
    @foreach ($model->ValeCompraModeloProdutoBarraS as $vcmpb)
      <li class='list-group-item'>
        <div class='row'>
          <div class='col-md-2'>
            {{ $vcmpb->ProdutoBarra->barras }}
          </div>
          <div class='col-md-5'>
            <?php $inativo = $vcmpb->ProdutoBarra->Produto->inativo; ?>
            @if (!empty($inativo))
              <s><a href='{{ url('produto', $vcmpb->ProdutoBarra->codproduto) }}'>{{ $vcmpb->ProdutoBarra->descricao() }}</a></s>
              <span class='text-danger'>
                  inativo desde {{ formataData($vcmpb->ProdutoBarra->Produto->inativo) }}
              </span>
            @else
              <a href='{{ url('produto', $vcmpb->ProdutoBarra->codproduto) }}'>
                {{ $vcmpb->ProdutoBarra->descricao() }}
              </a>
            @endif
            
          </div>
          <div class='col-md-2 text-right'>
            {{ formataNumero($vcmpb->quantidade, 3) }}
            {{ $vcmpb->ProdutoBarra->UnidadeMedida->sigla }}
          </div>
          <div class='col-md-1 text-right'>
            {{ formataNumero($vcmpb->preco, 2) }}
          </div>
          <div class='col-md-2 text-right'>
            {{ formataNumero($vcmpb->total, 2) }}
          </div>
        </div>
      </li>
    @endforeach
      <li class='list-group-item'>
        <b>
            @if (!empty($model->desconto))
                <div class='row'>
                  <div class='col-md-10 text-right'>
                    Total Produtos
                  </div>
                  <div class='col-md-2 text-right'>
                    {{ formataNumero($model->totalprodutos, 2) }}
                  </div>
                </div>

                <div class='row'>
                  <div class='col-md-10 text-right'>
                    Desconto
                  </div>
                  <div class='col-md-2 text-right'>
                    {{ formataNumero($model->desconto, 2) }}
                  </div>
                </div>
            @endif

            <div class='row'>
              <div class='col-md-10 text-right'>
                Total
              </div>
              <div class='col-md-2 text-right'>
                {{ formataNumero($model->total, 2) }}
              </div>
            </div>
        </b>
      </li>
    </ul>
  </div>
    
    
    <div class='col-md-4'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codvalecompramodelo }}</td> 
                    </tr>
                    <tr> 
                      <th>Vale Compra Modelo</th> 
                      <td>{{ $model->modelo }}</td> 
                    </tr>
                    <tr> 
                      <th>Codpessoafavorecido</th> 
                      <td>{{ $model->codpessoafavorecido }}</td> 
                    </tr>
                    <tr> 
                      <th>Turma</th> 
                      <td>{{ $model->turma }}</td> 
                    </tr>
                    <tr> 
                      <th>Observacoes</th> 
                      <td>{{ $model->observacoes }}</td> 
                    </tr>
                    <tr> 
                      <th>Totalprodutos</th> 
                      <td>{{ $model->totalprodutos }}</td> 
                    </tr>
                    <tr> 
                      <th>Desconto</th> 
                      <td>{{ $model->desconto }}</td> 
                    </tr>
                    <tr> 
                      <th>Total</th> 
                      <td>{{ $model->total }}</td> 
                    </tr>
                    <tr> 
                      <th>Ano</th> 
                      <td>{{ $model->ano }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->modelo }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->modelo }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->modelo }}'?" data-after="location.replace('{{ url('vale-compra-modelo') }}');"><i class="fa fa-trash"></i></a>                
    
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