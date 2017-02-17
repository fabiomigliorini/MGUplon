@extends('layouts.default')
@section('content')
<div class="col-sm-5 col-xs-12">
    <div class="card">
        <h3 class="card-header">
            {{ $model->grupousuario }}
            <div class="btn-group pull-right" role="group" aria-label="Controles">
                <a class="btn btn-secondary" href="{{ url("grupo-usuario/$model->codgrupousuario/edit") }}"><i class="fa fa-pencil"></i></a>
                @if (empty($model->inativo))
                    <a class="btn btn-secondary" href="{{ url("grupo-usuario/$model->codgrupousuario/inativar") }}" data-inativar data-pergunta="Tem certeza que deseja inativar '{{ $model->grupousuario }}'?" data-after="recarregaDiv('main-container')"><i class="fa fa-ban"></i></a>
                @else
                    <a class="btn btn-secondary" href="{{ url("grupo-usuario/$model->codgrupousuario/ativar") }}" data-inativar data-pergunta="Tem certeza que deseja ativar '{{ $model->grupousuario }}'?" data-after="recarregaDiv('main-container')"><i class="fa fa-circle-o"></i></a>
                @endif                
                <a class="btn btn-secondary" href="{{ url("grupo-usuario/$model->codgrupousuario") }}" data-excluir data-pergunta="Tem certeza que deseja excluir '{{ $model->grupousuario }}'?" data-after="location.replace('{{ url('grupo-usuario') }}');"><i class="fa fa-trash"></i></a>                
            </div>    
        </h3>
        <div class="card-block">
            @include('layouts.includes.inativo', [$model])
            <p class="card-text">
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codgrupousuario }}</td> 
                    </tr>
                    <tr> 
                      <th>Descrição</th> 
                      <td>{{ $model->grupousuario }}</td> 
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
<script type="text/javascript">
</script>
@endsection
@stop