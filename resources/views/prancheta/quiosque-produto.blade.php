@extends('layouts.quiosque')
@section('content')


<div class="card">
    <div class="card-header">
        <h3 class="modal-title" id="modal{{ $produto->codproduto }}label">
          {{ formataCodigo($produto->codproduto, 6) }} - {{ $produto->produto }}
        </h3>
    </div>
    <div class="card-block">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-5">
                <div id="car{{ $produto->codproduto }}lg" data-ride="carousel" class="carousel slide" data-interval="1500">
                    <div role="listbox" class="carousel-inner">
                        @foreach ($produto->imagem as $imagem)
                        <div class="carousel-item {{ ($loop->first)?'active':'' }} ">
                          <img class="d-block img-fluid" src="{{$imagem->url}}">
                        </div>
                        @endforeach
                    </div>
                    <a href="#car{{ $produto->codproduto }}lg" role="button" data-slide="prev" class="left carousel-control"> <span aria-hidden="true" class="fa fa-angle-left"></span> <span class="sr-only">Previous</span> </a>
                    <a href="#car{{ $produto->codproduto }}lg" role="button" data-slide="next" class="right carousel-control"> <span aria-hidden="true" class="fa fa-angle-right"></span> <span class="sr-only">Next</span> </a>
                </div>
            </div>
            <div class="col-md-2 ">

                <ul class="nav nav-pills nav-stacked" role="tablist">
                  @foreach ($produto->embalagem as $embalagem)
                  <li class="nav-item">
                    <a class="nav-link {{ ($loop->first)?'active':'' }}" data-toggle="tab" href="#variacoes{{ $produto->codproduto }}_{{ $embalagem->codprodutoembalagem }}" role="tab">
                      {{ $embalagem->sigla }} C/{{ formataNumero($embalagem->quantidade, 0) }} 
                      <b class="pull-right">
                      {{ formataNumero($embalagem->preco, 2) }}
                      </b>
                    </a>
                  </li>
                  @endforeach
                </ul>
              
                @if (!empty($produto->Marca->codimagem))
                  <img class='img-fluid' src='{{ asset("public/imagens/{$produto->Marca->Imagem->observacoes}") }}' alt='{{$produto->Marca->marca}}' title="{{$produto->Marca->marca}}">
                @else 
                  <hr>
                  <h3 class='text-center'>{{ $produto->Marca->marca }}</h3>
                  <hr>
                @endif
                
            </div>
            <div class='col-md-5 listagem-variacoes'>
                <div class="tab-content">
                  @foreach ($produto->embalagem as $embalagem)
                    <div class="tab-pane {{ ($loop->first)?'active':'' }}" id="variacoes{{ $produto->codproduto }}_{{ $embalagem->codprodutoembalagem }}" role="tabpanel">
                      <div class="list-group list-group-condensed">
                        @foreach ($embalagem->variacao as $variacao)
                          <div class="list-group-item list-group-item-action">
                            <div class='row'>
                              <div class='col-md-4 text-right'>
                                <small class='text-right'>
                                  @foreach ($variacao->barras as $barras)
                                    <a href="#" class="linkBarras" data-barras="{{ $barras->barras }}" data-dismiss="modal" >
                                      {{ $barras->barras }}
                                    </a><br>
                                  @endforeach
                                </small>
                              </div>
                              <div class='col-md-6'>
                                <b>{{ $variacao->variacao or '{Sem Variacao}' }}</b>
                              </div>
                              <div class='col-md-2 text-right'>
                                {{ formataNumero($variacao->saldoquantidade, 0) }}
                                <i class="fa fa-cubes"></i> 
                              </div>
                            </div>
                            
                            <div class="clearfix"></div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>
            </div>
          </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
        
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $("a.linkBarras").click(function (e) {
        e.preventDefault();
        var barras = $(this).data('barras');
        window.parent.adicionaProdutoPrancheta(barras);
    });
});
</script>
@endsection
@stop
