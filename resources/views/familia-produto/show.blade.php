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
                      <td>{{ formataCodigo($model->codfamiliaproduto) }}</td> 
                    </tr>
                    <tr> 
                      <th>Família Produto</th> 
                      <td>{{ $model->familiaproduto }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="collapse" id="collapsePesquisa">
          <div class="card">
            <h4 class="card-header">Pesquisar Grupos</h4>
            <div class="card-block">
                <div class="card-text">
                    {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                    {!! Form::hidden('codfamiliaproduto', $model->codfamiliaproduto, ['id'=>'codfamiliaproduto']) !!}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="codgrupoproduto" class="control-label">#</label>
                                {!! Form::number('codgrupoproduto', null, ['class'=> 'form-control', 'id'=>'codgrupoproduto', 'step'=>1, 'min'=>1]) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="grupoproduto" class="control-label">Grupo</label>
                                {!! Form::text('grupoproduto', null, ['class'=> 'form-control', 'id'=>'grupoproduto']) !!}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="inativo" class="control-label">Ativos</label>
                                {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    {!! Form::close() !!} 
                    <div class='clearfix'></div>
                </div>
            </div>
          </div>
        </div>
        <div class='card'>
            <h4 class="card-header">
                Grupos
                <div class="btn-group pull-right">
                    <a class="btn btn-secondary btn-sm" href="{{ url("grupo-produto/create?codfamiliaproduto={$model->codfamiliaproduto}") }}"><i class="fa fa-plus"></i></a> 
                    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>                
                </div>
            </h4>
            <div class='card-block table-responsive'>       
                @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Grupo']])
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>


@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("familia-produto/$model->codfamiliaproduto/edit") }}"><i class="fa fa-pencil"></i></a>
    @if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("familia-produto/$model->codfamiliaproduto/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->familiaproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("familia-produto/$model->codfamiliaproduto/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->familiaproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("familia-produto/$model->codfamiliaproduto") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->familiaproduto }}'?" data-after="location.replace('{{ url("secao-produto/{$model->codsecaoproduto}") }}');"><i class="fa fa-trash"></i></a>
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection


@section('inscript')
    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('grupo-produto/datatable'), 'order' => $filtro['order'], 'filtros' => ['codfamiliaproduto', 'codgrupoproduto' => 'codgrupoproduto', 'grupoproduto', 'inativo'] ])
@endsection
@stop