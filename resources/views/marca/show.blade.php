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
                      <td>{{ formataCodigo($model->codmarca) }}</td> 
                    </tr>
                    <tr> 
                      <th>Marca</th> 
                      <td>{{ $model->marca }}</td> 
                    </tr>
                    <tr> 
                      <th>Disponível no site</th> 
                      <td>{{ ($model->site == 1  ?'Sim':'Não') }}</td> 
                    </tr>
                    <tr> 
                      <th>Descrição</th> 
                      <td>{{ $model->descricaosite }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
    <div class='col-md-4'>
        <div class='card'>
            <h4 class="card-header">Imagem</h4>
            <div class='card-block'>
                @if($model->codimagem)
                <div class="text-right">
                    <a href="{{ url("/imagem/$model->codmarca/delete/?model=Marca&imagem={$model->Imagem->codimagem}") }}" class="btn btn-secondary btn-sm"><i class="fa fa-trash"></i> Excluir</a>
                    <a href="{{ url("/imagem/edit?id=$model->codmarca&model=Marca") }}" class="btn btn-secondary btn-sm"><i class="fa fa-pencil"></i> Alterar</a>
                </div>        
                <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
                    <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->arquivo);?>'>
                </a>
                @else
                <a title="Cadastrar imagem" href="{{ url("/imagem/create?model=marca&id=$model->codmarca") }}" class="btn btn-secondary">
                    <i class="fa fa-picture-o"></i>
                    Cadastrar imagem
                </a>
                @endif
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("marca/$model->codmarca/edit") }}"><i class="fa fa-pencil"></i></a>
    @if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("marca/$model->codmarca/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->marca }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("marca/$model->codmarca/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->marca }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("marca/$model->codmarca") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->marca }}'?" data-after="location.replace('{{ url('marca') }}');"><i class="fa fa-trash"></i></a>                
    
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