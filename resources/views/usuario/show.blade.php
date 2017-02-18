@extends('layouts.default')
@section('content')
<div class="col-sm-7 col-xs-12">
    <div class="card">
        <h3 class="card-header">
            {{ $model->usuario }}
            <div class="btn-group pull-right" role="group" aria-label="Controles">
                <a class="btn btn-secondary" href="{{ url("usuario/$model->codusuario/edit") }}"><i class="fa fa-pencil"></i></a>
                @if (empty($model->inativo))
                    <a class="btn btn-secondary" href="{{ url("usuario/$model->codusuario/inativar") }}" data-inativar data-pergunta="Tem certeza que deseja inativar '{{ $model->usuario }}'?" data-after="recarregaDiv('main-container')"><i class="fa fa-ban"></i></a>
                @else
                    <a class="btn btn-secondary" href="{{ url("usuario/$model->codusuario/ativar") }}" data-inativar data-pergunta="Tem certeza que deseja ativar '{{ $model->usuario }}'?" data-after="recarregaDiv('main-container')"><i class="fa fa-circle-o"></i></a>
                @endif                
                <a class="btn btn-secondary" href="{{ url("usuario/$model->codusuario") }}" data-excluir data-pergunta="Tem certeza que deseja excluir '{{ $model->usuario }}'?" data-after="location.replace('{{ url('usuario') }}');"><i class="fa fa-trash"></i></a>                
            </div>    
        </h3>
        <div class="card-block">
            @include('layouts.includes.inativo', [$model])
            <p class="card-text">
                <table class="table table-bordered table-striped table-hover table-sm col-md-12">
                    <tbody>  
                        <tr> 
                            <th>#</th> 
                            <td>{{ formataCodigo($model->codusuario) }}</td> 
                        </tr>
                        <tr> 
                            <th>Usuário</th> 
                            <td>{{ $model->usuario }}</td> 
                        </tr>
                        <tr> 
                            <th>Filial</th> 
                            <td>{{ $model->Filial->filial or '' }}</td> 
                        </tr>
                        <tr> 
                            <th>Pessoa</th> 
                            <td>{{ $model->Pessoa->pessoa or ''}}</td> 
                        </tr>
                        <tr> 
                            <th>Impressora Matricial</th> 
                            <td>{{ $model->impressoramatricial }}</td> 
                        </tr> 
                        <tr> 
                            <th>Impressora Térmica</th> 
                            <td>{{ $model->impressoratermica }}</td> 
                        </tr>
                        <tr> 
                            <th>Impressora tela negócio</th> 
                            <td>{{ $model->impressoratelanegocio }}</td> 
                        </tr>
                        <tr> 
                            <th>Último acesso</th> 
                            <td>{{ formataData($model->ultimoacesso, 'L') }}</td> 
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


