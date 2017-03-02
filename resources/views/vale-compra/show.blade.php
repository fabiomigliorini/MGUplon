@extends('layouts.default')
@section('content')

<div class="row">
  <div class='col-md-8'>
    <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
    @foreach ($model->ValeCompraProdutoBarraS as $vcmpb)
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
    <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              #
            </div>
            <div class='col-md-8'>
              {{ formataCodigo($model->codvalecompra) }}
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Aluno
              <br>
              <small class="text-muted">
                Turma
              </small>
            </div>
            <div class='col-md-8'>
              {{ $model->aluno }}
              <br>
              <small class="text-muted">
                {{ $model->turma }}
              </small>
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Pessoa
            </div>
            <div class='col-md-8'>
              <a href="{{ url('pessoa', $model->codpessoa) }}">
                {{ $model->Pessoa->fantasia }}
              </a>
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Pagamento
            </div>
            @foreach ($model->ValeCompraFormaPagamentoS as $pag)
              <div class='col-md-8'>
                {{ $pag->FormaPagamento->formapagamento }}
                @foreach ($pag->TituloS as $titulo)
                  <br>
                  <a href='{{ url('titulo', $titulo->codtitulo) }}'>{{ $titulo->numero }}</a>
                  <small class="pull-right text-muted">
                    Saldo: {{ formataNumero($titulo->saldo) }}
                  </small>
                @endforeach
              </div>
            @endforeach
        </div>
      </li>      
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Favorecido
              <br>
              <small class="text-muted">
                Modelo
              </small>
            </div>
            <div class='col-md-8'>
              <a href='{{ url('pessoa', $model->codpessoafavorecido) }}'>
                {{ $model->PessoaFavorecido->fantasia }}
              </a>
              <br>
              <small class="text-muted">
                <a href='{{ url('vale-compra-modelo', $model->codvalecompramodelo) }}'>
                  {{ $model->ValeCompraModelo->modelo }}
                </a>
              </small>
              
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Crédito
            </div>
            <div class='col-md-8'>
              <a href='{{ url('titulo', $model->codtitulo) }}'>
                {{ $model->Titulo->numero }}
              </a>
              <small class="pull-right text-muted">
                Saldo: {{ formataNumero($model->Titulo->saldo * -1) }}
              </small>
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Observações
            </div>
            <div class='col-md-8'>
              {!! nl2br($model->observacoes) !!}
            </div>
        </div>
      </li>
    </ul>
  </div>
</div>

<!-- Large modal -->

<div class="modal fade" id="modalRelatorio" tabindex="-1" role="dialog" aria-labelledby="modalRelatorioLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <iframe id="frameImpressao" src="" style="border: 0px; width: 100%; height: 300px">
        </iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button class="btn btn-primary" title="Imprimir" id="btnImprimir">
            <i class="fa fa-print"></i>
        </button>
      </div>
    </div>
  </div>
</div>

@section('buttons')

    @if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra/$model->codvalecompra/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar o vale '{{ formataCodigo($model->codvalecompra) }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra/$model->codvalecompra/activate") }}" data-activate data-question="Tem certeza que deseja ativar o vale '{{ formataCodigo($model->codvalecompra) }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" title='Impressão' href="#" data-toggle="modal" data-target="#modalRelatorio" id="linkImpressao"><span class="fa fa-print"></span></a>
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    
    @if ($imprimir == true)
        $('#frameImpressao').attr('src', '{{ url("vale-compra/{$model->codvalecompra}/imprimir?imprimir=true") }}');
        $('#modalRelatorio').modal('show')
    @endif
    
    $('#linkImpressao').click(function (e) {
        $('#frameImpressao').attr('src', '{{ url("vale-compra/{$model->codvalecompra}/imprimir?imprimir=false") }}');
    });
    
    
    $('#btnImprimir').click(function (e) {
        $('#frameImpressao').attr('src', '{{ url("vale-compra/{$model->codvalecompra}/imprimir?imprimir=true") }}');
        $('#modalRelatorio').modal('hide')
        bootbox.alert('Documento enviado para impressora!');
    });
    
});
</script>
@endsection
@stop