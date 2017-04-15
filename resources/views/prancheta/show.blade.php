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


@endsection
@stop