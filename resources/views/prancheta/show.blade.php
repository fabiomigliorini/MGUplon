@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-4'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codprancheta }}</td> 
                    </tr>
                    <tr> 
                      <th>Prancheta</th> 
                      <td>{{ $model->prancheta }}</td> 
                    </tr>
                    <tr> 
                      <th>Observacoes</th> 
                      <td>{{ $model->observacoes }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>
<div class='row'>
@foreach ($itens['secaoproduto'] as $secao)
  @foreach ($secao->familiaproduto as $familia)
    @foreach ($familia->grupoproduto as $grupo)
      @foreach ($grupo->subgrupoproduto as $subgrupo)
        <!--
        <h1>
        {{ $secao->secaoproduto }} >> {{ $familia->familiaproduto }} >> {{ $grupo->grupoproduto }} >> {{ $subgrupo->subgrupoproduto }}
        </h1>
        <div class='row'>
        -->
        @foreach ($subgrupo->produto as $produto)
          <?php $produtos[$produto->codproduto] = $produto; ?>
          <div class="col-md-2">
            <div class="card">
              <div id="car{{ $produto->codproduto }}" class="carousel slide" data-ride="carousel" data-interval="1500">
                <div class="carousel-inner" role="listbox">
                  @foreach ($produto->imagem as $imagem)
                  <div class="carousel-item {{ ($loop->first)?'active':'' }} ">
                    <img class="d-block img-fluid" src="{{$imagem->url}}" alt="First slide">
                  </div>
                  @endforeach
                </div>
              </div>              
              <div class="card-block">
                <h4>{{ $produto->produto }}</h4>
                @foreach ($produto->embalagem as $embalagem) 
                  <a href="#" class="flex-column align-items-start codprodutoembalagem" data-codprodutoembalagem='{{ $embalagem->codprodutoembalagem }}' data-codproduto='{{ $produto->codproduto }}'>
                    <div class="d-flex w-100 justify-content-between">
                        <small class="text-muted">
                          {{ $embalagem->sigla }}
                          C/{{ formataNumero($embalagem->quantidade, 0) }}
                        </small>
                        <span class='pull-right'>
                          {{ formataNumero($embalagem->preco, 2) }} 
                        </span>
                    </div>
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        @endforeach
        <!--
        </div>
        -->
      @endforeach 
    @endforeach 
  @endforeach 
@endforeach 
</div>
<hr>
<pre>
<?php
print_r($itens);

?>
</pre>
<?php /*
@foreach ($produtos['produtos'] as $prod) 
  <div class='row'>
    <div class='col-md-12'>
        <div class='card'>
          <h4 class="card-header">
            {{$prod[0]->secaoproduto}} /
            {{$prod[0]->familiaproduto}} /
            {{$prod[0]->grupoproduto}} /
            {{$prod[0]->subgrupoproduto}}
        </h4>
          <div class='card-block'>
    
  @foreach ($prod as $p)
    <div class="col-md-2">
        <!-- Simple card -->
        <div class="card">
            @if (!empty($produtos['imagens'][$p->codproduto]))
              <img class="card-img-top img-fluid" src="{{ asset('public/imagens/' . $produtos['imagens'][$p->codproduto][0]->Imagem->observacoes) }}" alt="Card image cap">
            @endif
            <div class="card-block">
                <h6 class="card-title">
                  <a href="{{ url("produto/{$p->codproduto}") }}">
                  {{ $p->produto }} 
                  </a>
                </h6>
              <span class="text-muted pull-left">

                  @if (!empty($p->variacao))
                  <small class="text-muted">
                      {{ $p->variacao }}
                  </small>
                  @endif
                  @if (!empty($p->quantidade))
                  <small class="text-muted">
                      {{ $p->siglaembalagem }} C/
                      {{ formatanumero($p->quantidade, 0) }}
                  </small>
                  @endif
                {{ $p->barras }}
              </span>
                <h1 class="card-title text-primary pull-right">
                    {{ formataNumero($p->preco) }}
                </h1>
              <div class="clearfix"></div>
            </div>
        </div>
    </div>
  @endforeach
  </div>
        </div>
    </div>
  </div>
@endforeach
 * 
 */
?>
@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("prancheta/$model->codprancheta/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("prancheta/$model->codprancheta/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->prancheta }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("prancheta/$model->codprancheta/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->prancheta }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("prancheta/$model->codprancheta") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->prancheta }}'?" data-after="location.replace('{{ url('prancheta') }}');"><i class="fa fa-trash"></i></a>                
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<script type="text/javascript">
    
var produtos = {!! json_encode($produtos) !!};

function selecionaEmbalagem(codproduto, codprodutoembalagem) {
    var produto = produtos[codproduto];
    if (codprodutoembalagem == '') codprodutoembalagem = 0;
    var embalagem = produto.embalagem[codprodutoembalagem];
    var qtdVariacao = Object.keys(embalagem.variacao).length;
    if (qtdVariacao == 1) {
        var codprodutovariacao = null;
        $.each(embalagem.variacao, function(i, item) {
            codprodutovariacao = item.codprodutovariacao;
        });
        selecionaVariacao(codproduto, codprodutoembalagem, codprodutovariacao);
    }
}

function selecionaVariacao(codproduto, codprodutoembalagem, codprodutovariacao) {
    var barras = null;
    $.each(produtos[codproduto].embalagem[codprodutoembalagem].variacao[codprodutovariacao].barras, function(i, item) {
        barras = item;
    });
    console.log(barras.barras);
}
    
$(document).ready(function() {
    $("a.codprodutoembalagem").click(function (e) {
        e.preventDefault();
        var codproduto = $(this).data('codproduto');
        var codprodutoembalagem = $(this).data('codprodutoembalagem');
        selecionaEmbalagem (codproduto, codprodutoembalagem);
    });
});
</script>
@endsection
@stop
