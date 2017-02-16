@extends('layouts.default')
@section('content')

<div class="col-sm-5 col-xs-12">
    <div class="card">
        <h3 class="card-header">
            {{ $model->unidademedida }}
            <div class="btn-group pull-right" role="group" aria-label="Controles">
                <a class="btn btn-secondary" href="{{ url("unidade-medida/$model->codunidademedida/edit") }}"><i class="fa fa-pencil"></i></a>
                @if (empty($model->inativo))
                    <a class="btn btn-secondary" href="{{ url("unidade-medida/$model->codunidademedida/inativar") }}" data-inativar data-pergunta="Tem certeza que deseja inativar '{{ $model->unidademedida }}'?" data-after="recarregaDiv('main-container')"><i class="fa fa-ban"></i></a>
                @else
                    <a class="btn btn-secondary" href="{{ url("unidade-medida/$model->codunidademedida/ativar") }}" data-inativar data-pergunta="Tem certeza que deseja ativar '{{ $model->unidademedida }}'?" data-after="recarregaDiv('main-container')"><i class="fa fa-circle-o"></i></a>
                @endif
                <a class="btn btn-secondary" href="{{ url("unidade-medida/$model->codunidademedida") }}" data-excluir data-pergunta="Tem certeza que deseja excluir '{{ $model->unidademedida }}'?" data-after-delete="location.replace('{{ url('unidade-medida') }}');"><i class="fa fa-trash"></i></a>                
            </div>    
        </h3>
        <div class="card-block">
            @include('layouts.includes.inativo', [$model])
            <p class="card-text">
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codunidademedida }}</td> 
                    </tr>
                    <tr> 
                      <th>Descrição</th> 
                      <td>{{ $model->unidademedida }}</td> 
                    </tr>
                    <tr> 
                      <th>Sigla</th> 
                      <td>{{ $model->sigla }}</td> 
                    </tr>
                  </tbody> 
                </table>
            </p>
            <p class="card-text">
            @include('layouts.includes.criacao', [$model])
            </p>
        </div>
    </div>
</div>

@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop