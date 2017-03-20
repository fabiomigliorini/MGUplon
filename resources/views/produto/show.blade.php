@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-6'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codproduto }}</td> 
                    </tr>
                    <tr> 
                      <th>Produto</th> 
                      <td>{{ $model->codproduto }}</td> 
                    </tr>
                    <tr> 
                      <th>Produto</th> 
                      <td>{{ $model->produto }}</td> 
                    </tr>
                    <tr> 
                      <th>Referencia</th> 
                      <td>{{ $model->referencia }}</td> 
                    </tr>
                    <tr> 
                      <th>Codunidademedida</th> 
                      <td>{{ $model->codunidademedida }}</td> 
                    </tr>
                    <tr> 
                      <th>Codsubgrupoproduto</th> 
                      <td>{{ $model->codsubgrupoproduto }}</td> 
                    </tr>
                    <tr> 
                      <th>Codmarca</th> 
                      <td>{{ $model->codmarca }}</td> 
                    </tr>
                    <tr> 
                      <th>Preco</th> 
                      <td>{{ $model->preco }}</td> 
                    </tr>
                    <tr> 
                      <th>Importado</th> 
                      <td>{{ $model->importado }}</td> 
                    </tr>
                    <tr> 
                      <th>Codtributacao</th> 
                      <td>{{ $model->codtributacao }}</td> 
                    </tr>
                    <tr> 
                      <th>Codtipoproduto</th> 
                      <td>{{ $model->codtipoproduto }}</td> 
                    </tr>
                    <tr> 
                      <th>Site</th> 
                      <td>{{ $model->site }}</td> 
                    </tr>
                    <tr> 
                      <th>Descricaosite</th> 
                      <td>{{ $model->descricaosite }}</td> 
                    </tr>
                    <tr> 
                      <th>Codncm</th> 
                      <td>{{ $model->codncm }}</td> 
                    </tr>
                    <tr> 
                      <th>Codcest</th> 
                      <td>{{ $model->codcest }}</td> 
                    </tr>
                    <tr> 
                      <th>Observacoes</th> 
                      <td>{{ $model->observacoes }}</td> 
                    </tr>
                    <tr> 
                      <th>Codopencart</th> 
                      <td>{{ $model->codopencart }}</td> 
                    </tr>
                    <tr> 
                      <th>Codopencartvariacao</th> 
                      <td>{{ $model->codopencartvariacao }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
    <div class='col-md-6'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab-variacoes" role="tab">Detalhes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-estoque" role="tab">Estoque</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-site" role="tab">Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-fiscal" role="tab">NCM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-negocio" role="tab">Negócios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#nfes" role="tab">NFE's</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#observacoes" role="tab">Observações</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-variacoes" role="tabpanel">Detalhes</div>
                    <div class="tab-pane" id="tab-estoque" role="tabpanel">Estoque</div>
                    <div class="tab-pane" id="tab-site" role="tabpanel">Site</div>
                    <div class="tab-pane" id="tab-fiscal" role="tabpanel">NCM</div>
                    <div class="tab-pane" id="tab-negocio" role="tabpanel">Negócios</div>
                    <div class="tab-pane" id="nfes" role="tabpanel">NFE's</div>
                    <div class="tab-pane" id="observacoes" role="tabpanel">Observações</div>
                </div>
            </div>    
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("produto/$model->codproduto/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("produto/$model->codproduto/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("produto/$model->codproduto/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("produto/$model->codproduto") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->codproduto }}'?" data-after="location.replace('{{ url('produto') }}');"><i class="fa fa-trash"></i></a>                
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<style type="text/css">
    .nav-tabs .nav-link {
        padding: 0.5em 0.7em !important;
    }
</style>
@endsection
@stop