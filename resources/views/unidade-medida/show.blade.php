@extends('layouts.default')
@section('content')

<div class="col-sm-5 col-xs-12">
    <div class="card">
        <h3 class="card-header">
            {{ $model->unidademedida }}
            <div class="btn-group pull-right" role="group" aria-label="Controles">
                <a class="btn btn-secondary" href="{{ url("unidade-medida/$model->codunidademedida/edit") }}"><i class="fa fa-pencil"></i></a>
                <!--<a class="btn btn-secondary" href="{{ url("unidade-medida/$model->codunidademedida/delete") }}"><i class="fa fa-trash"></i></a>-->
                <a class="btn btn-secondary" href="{{ url("unidade-medida/$model->codunidademedida") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a unidade de medida '{{ $model->unidademedida }}'?" data-after-delete="location.replace(baseUrl + '/unidade-medida');"><i class="fa fa-trash"></i></a>                
            </div>    
        </h3>
        <div class="card-block">
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
              <small class="text-muted">
                Criado em 07/02/2017 15:54 por <a href=http://192.168.1.205/MGLara/usuario/10000004>alisson</a> 
                Alterado em 13/02/2017 09:50 por <a href=http://192.168.1.205/MGLara/usuario/1>fabio</a>                  
              </small>
            </p>
        </div>
    </div>
</div>

@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop