@extends('layouts.default')
@section('content')


<div class='card'>
    <div class='card-block'>
<div class="portfolioFilter">
  <a href="#" data-filter="*" class="current waves-effect waves-light">Todos</a>
  @foreach ($itens['marca']->sortBy('marca') as $marca)
  <a href="#" data-filter=".marca-{{ $marca->codmarca }}" class="waves-effect waves-light">
      @if (!empty($marca->codimagem))
      <img height="50" src='{{ asset("public/imagens/{$marca->Imagem->observacoes}") }}' alt='{{$marca->marca}}' title="{{$marca->marca}}">
      @else
        {{ $marca->marca }}
      @endif
  </a>
  @endforeach
  @foreach ($itens['secao']->sortBy('secaoproduto') as $secao)
    <a href="#" data-filter=".secao-{{ $secao->codsecaoproduto }}" class="waves-effect waves-light">{{ $secao->secaoproduto }}</a>
    @foreach ($itens['familia']->where('codsecaoproduto', $secao->codsecaoproduto)->sortBy('familiaproduto') as $familia)
      <a href="#" data-filter=".familia-{{ $familia->codfamiliaproduto }}" class="waves-effect waves-light">{{ $familia->familiaproduto }}</a>
      @foreach ($itens['grupo']->where('codfamiliaproduto', $familia->codfamiliaproduto)->sortBy('grupoproduto') as $grupo)
        <a href="#" data-filter=".grupo-{{ $grupo->codgrupoproduto }}" class="waves-effect waves-light">{{ $grupo->grupoproduto }}</a>
          @foreach ($itens['subgrupo']->where('codgrupoproduto', $grupo->codgrupoproduto)->sortBy('subgrupoproduto') as $subgrupo)
            <a href="#" data-filter=".subgrupo-{{ $subgrupo->codsubgrupoproduto }}" class="waves-effect waves-light">{{ $subgrupo->subgrupoproduto }}</a>
          @endforeach
      @endforeach
    @endforeach
  @endforeach
</div>
</div>
</div>
            

  <div class="row port m-b-20">
    <div class="portfolioContainer">
      @foreach ($itens['produto'] as $produto)
        <div class="col-md-2 marca-{{ $produto->codmarca }} secao-{{ $produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto }} familia-{{ $produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto }}  grupo-{{ $produto->SubGrupoProduto->codgrupoproduto }} subgrupo-{{ $produto->codsubgrupoproduto }} ">
          <div class="thumb">
            <a href="{{ url("prancheta/{$model->codprancheta}/produto/{$produto->codproduto}/$codestoquelocal") }}" class="image-popup" title="{{ $produto->produto }}">
              @if (isset($itens['imagem'][$produto->codproduto]))
                <div id="car{{ $produto->codproduto }}" class="carousel slide" data-ride="carousel" data-interval="1500" >
                  <div class="carousel-caption d-none d-md-block" style="">
                    <h4><small>R$</small> {{ formataNumero($produto->preco, 2) }}</h4>
                    <i class="fa fa-cubes"></i> 
                    @if (!empty($produto->saldoquantidade))
                      {{ formataNumero($produto->saldoquantidade, 0) }}
                    @else
                      Sem Saldo
                    @endif
                  </div>
                  <div class="carousel-inner" role="listbox">
                      @foreach ($itens['imagem'][$produto->codproduto] as $imagem)
                        <div class="carousel-item {{ ($loop->first)?'active':'' }} ">
                          <img class="d-block img-fluid thumb-img" src="{{$imagem->url}}" alt="First slide">
                        </div>
                      @endforeach
                  </div>
                </div>
              @else
                <div class="gal-detail text-xs-center">
                  <h4 class="m-t-10">{{ $produto->produto }}</h4>
                  <h4><small>R$</small> {{ formataNumero($produto->preco, 2) }}</h4>
                  <p class="text-muted">
                    <i class="fa fa-cubes"></i>
                    @if (!empty($produto->saldoquantidade))
                      {{ formataNumero($produto->saldoquantidade, 0) }}
                    @else
                      Sem Saldo
                    @endif
                  </p>
                </div>
              @endif              
            </a>
          </div>
        </div>
      @endforeach
    </div><!-- end portfoliocontainer-->
  </div> <!-- End row -->

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
<script src='http://localhost/MGUplon.original/Uplon/Admin/PHP/Vertical/assets/plugins/isotope/js/isotope.pkgd.min.js'></script>
<script src='http://localhost/MGUplon.original/Uplon/Admin/PHP/Vertical/assets/plugins/magnific-popup/js/jquery.magnific-popup.min.js'></script>
<script type="text/javascript">
$(document).ready(function() {
    $("a.linkBarras").click(function (e) {
        e.preventDefault();
        var barras = $(this).data('barras');
        console.log(barras);
    });
    
    
});


/**
* Theme: Uplon Admin Template
* Author: Coderthemes
* Component: Peity Chart
* 
*/

$(window).load(function(){
    var $container = $('.portfolioContainer');
    $container.isotope({
        filter: '*',
        animationOptions: {
            duration: 750,
            easing: 'linear',
            queue: false
        }
    });

    $('.portfolioFilter a').click(function(){
        $('.portfolioFilter .current').removeClass('current');
        $(this).addClass('current');

        var selector = $(this).attr('data-filter');
        $container.isotope({
            filter: selector,
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
        return false;
    });
});
</script>

@endsection
@stop
