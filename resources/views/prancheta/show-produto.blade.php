@extends('layouts.default')
@section('content')


            <div class="card">
                    <div class="card-header">
                        <h4 class="modal-title" id="modal{{ $produto->codproduto }}label">
                          <a href="{{ url('produto', $produto->codproduto) }}">
                            {{ $produto->produto }}
                          </a>
                        </h4>
                    </div>
                <div class="card-block">
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-3 ">
                          
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
                        </div>
                        <div class='col-md-5 listagem-variacoes'>
                            <div class="tab-content">
                              @foreach ($produto->embalagem as $embalagem)
                                <div class="tab-pane {{ ($loop->first)?'active':'' }}" id="variacoes{{ $produto->codproduto }}_{{ $embalagem->codprodutoembalagem }}" role="tabpanel">
                                  <div class="list-group list-group-condensed">
                                    @foreach ($embalagem->variacao as $variacao)
                                      <div class="list-group-item list-group-item-action">
                                        {{ $variacao->variacao or '{Sem Variacao}' }}
                                        <small class="text-muted pull-right" >
                                          @foreach ($variacao->barras as $barras)
                                            <a href="#" class="linkBarras" data-barras="{{ $barras->barras }}" data-dismiss="modal" >
                                              {{ $barras->barras }}
                                            </a><br>
                                          @endforeach
                                        </small>
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
$(document).ready(function() {
    $("a.linkBarras").click(function (e) {
        e.preventDefault();
        var barras = $(this).data('barras');
        console.log(barras);
    });
});
</script>
<style>
  .produto:hover h3 {
      background-color: blue;
      color: yellow;
      border-radius: 3px;
  }
  
  .listagem-variacoes {
    max-height: calc(100vh - 300px);
    overflow-y: auto;
  }
</style>
@endsection
@stop
