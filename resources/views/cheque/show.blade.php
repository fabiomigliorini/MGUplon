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
                      <td>{{ $model->codcheque }}</td> 
                    </tr>
                    <tr> 
                      <th>Cheque</th> 
                      <td>{{ $model->codcheque }}</td> 
                    </tr>
                    <tr> 
                      <th>Cmc7</th> 
                      <td>{{ $model->cmc7 }}</td> 
                    </tr>
                    <tr> 
                      <th>Codbanco</th> 
                      <td>{{ $model->codbanco }}</td> 
                    </tr>
                    <tr> 
                      <th>Agencia</th> 
                      <td>{{ $model->agencia }}</td> 
                    </tr>
                    <tr> 
                      <th>Contacorrente</th> 
                      <td>{{ $model->contacorrente }}</td> 
                    </tr>
                    <tr> 
                      <th>Emitente</th> 
                      <td>{{ $model->emitente }}</td> 
                    </tr>
                    <tr> 
                      <th>Numero</th> 
                      <td>{{ $model->numero }}</td> 
                    </tr>
                    <tr> 
                      <th>Emissao</th> 
                      <td>{{ $model->emissao }}</td> 
                    </tr>
                    <tr> 
                      <th>Vencimento</th> 
                      <td>{{ $model->vencimento }}</td> 
                    </tr>
                    <tr> 
                      <th>Repasse</th> 
                      <td>{{ $model->repasse }}</td> 
                    </tr>
                    <tr> 
                      <th>Destino</th> 
                      <td>{{ $model->destino }}</td> 
                    </tr>
                    <tr> 
                      <th>Devolucao</th> 
                      <td>{{ $model->devolucao }}</td> 
                    </tr>
                    <tr> 
                      <th>Motivodevolucao</th> 
                      <td>{{ $model->motivodevolucao }}</td> 
                    </tr>
                    <tr> 
                      <th>Observacao</th> 
                      <td>{{ $model->observacao }}</td> 
                    </tr>
                    <tr> 
                      <th>Lancamento</th> 
                      <td>{{ $model->lancamento }}</td> 
                    </tr>
                    <tr> 
                      <th>Cancelamento</th> 
                      <td>{{ $model->cancelamento }}</td> 
                    </tr>
                    <tr> 
                      <th>Valor</th> 
                      <td>{{ $model->valor }}</td> 
                    </tr>
                    <tr> 
                      <th>Codpessoa</th> 
                      <td>{{ $model->codpessoa }}</td> 
                    </tr>
                    <tr> 
                      <th>Indstatus</th> 
                      <td>{{ $model->indstatus }}</td> 
                    </tr>
                    <tr> 
                      <th>Codtitulo</th> 
                      <td>{{ $model->codtitulo }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("cheque/$model->codcheque/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("cheque/$model->codcheque/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codcheque }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("cheque/$model->codcheque/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codcheque }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("cheque/$model->codcheque") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->codcheque }}'?" data-after="location.replace('{{ url('cheque') }}');"><i class="fa fa-trash"></i></a>                
    
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