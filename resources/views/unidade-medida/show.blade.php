@extends('layouts.default')
@section('content')
<div class="card-box col-md-5">
  <h4 class="header-title m-t-0">
    &nbsp;
    <span class="pull-right">
      <a href="{{ url("unidade-medida/create") }}"><i class="fa fa-plus"></i></a> 
      <a href="{{ url("unidade-medida/$model->codunidademedida/edit") }}"><i class="fa fa-pencil"></i></a>
    </span>
  </h4>  
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
  
    <small class="text-muted">
                Criado
            em 07/02/2017 15:54             por <a href=http://192.168.1.205/MGLara/usuario/10000004>alisson</a> 
                    Alterado
            em 13/02/2017 09:50             por <a href=http://192.168.1.205/MGLara/usuario/1>fabio</a> 
    </small>  
</div>

@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop