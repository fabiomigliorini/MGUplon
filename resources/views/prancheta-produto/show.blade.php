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
                      <td>{{ $model->codpranchetaproduto }}</td> 
                    </tr>
                    <tr> 
                      <th>Produtos da Prancheta</th> 
                      <td>{{ $model->codproduto }}</td> 
                    </tr>
                    <tr> 
                      <th>Codprancheta</th> 
                      <td>{{ $model->codprancheta }}</td> 
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

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("prancheta-produto/$model->codpranchetaproduto/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("prancheta-produto/$model->codpranchetaproduto/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("prancheta-produto/$model->codpranchetaproduto/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("prancheta-produto/$model->codpranchetaproduto") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->codproduto }}'?" data-after="location.replace('{{ url('prancheta-produto') }}');"><i class="fa fa-trash"></i></a>                
    
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