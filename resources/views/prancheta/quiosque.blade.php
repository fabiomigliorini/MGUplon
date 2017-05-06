@extends('layouts.quiosque')
@section('content')

<div class='card'>
    <div class='card-block'>
<div class="portfolioFilter">
  <a href="#" data-filter="*" class="current waves-effect waves-light">Todos</a>
  @foreach ($itens['prancheta']->sortBy('prancheta') as $prancheta)
    <a href="#" data-filter=".prancheta-{{ $prancheta->codprancheta }}" class="waves-effect waves-light">{{ $prancheta->prancheta }}</a>
  @endforeach
  @foreach ($itens['marca']->sortBy('marca') as $marca)
  <a href="#" data-filter=".marca-{{ $marca->codmarca }}" class="waves-effect waves-light">
    @if (!empty($marca->codimagem))
      <img height="50" src='{{ asset("public/imagens/{$marca->Imagem->observacoes}") }}' alt='{{$marca->marca}}' title="{{$marca->marca}}">
    @else
      {{ $marca->marca }}
    @endif
  </a>
  @endforeach
</div>
</div>
</div>
            

  <div class="row port m-b-20">
    <div class="portfolioContainer">
      @foreach ($itens['produto'] as $produto)
        <div class="col-md-2 marca-{{ $produto->codmarca }} prancheta-{{ $produto->codprancheta }} ">
          <div class="thumb">
            <a href="{{ url("prancheta/quiosque/produto/{$produto->codpranchetaproduto}/{$codestoquelocal}") }}" class="image-popup" title="{{ $produto->produto }}">
              @if ($itens['imagem'][$produto->codproduto]->count() > 0)
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
                          <img class="d-block img-fluid thumb-img" src="{{$imagem->url}}" alt="{{ $produto->produto }}">
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
