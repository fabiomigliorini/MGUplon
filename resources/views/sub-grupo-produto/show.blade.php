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
                      <td>{{ formataCodigo($model->codsubgrupoproduto) }}</td> 
                    </tr>
                    <tr> 
                      <th>Grupo Produto</th> 
                      <td>{{ $model->subgrupoproduto }}</td> 
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
            <h4 class="card-header">Pesquisar Produtos</h4>
            <div class="card-block">
                <div class="card-text">
                    {!! Form::model($filtro, ['id' => 'form-search', 'autocomplete' => 'on']) !!}
                    {!! Form::hidden('codgrupoproduto', $model->codgrupoproduto, ['id'=>'codgrupoproduto']) !!}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="codsubsubgrupoproduto" class="control-label">#</label>
                                {!! Form::number('codsubsubgrupoproduto', null, ['class'=> 'form-control', 'id'=>'codsubsubgrupoproduto', 'step'=>1, 'min'=>1]) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="subsubgrupoproduto" class="control-label">Sub Grupo</label>
                                {!! Form::text('subsubgrupoproduto', null, ['class'=> 'form-control', 'id'=>'subsubgrupoproduto']) !!}
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
                Produtos
                <div class="btn-group pull-right">
                    <a class="btn btn-secondary btn-sm" href="{{ url("sub-grupo-produto/create?codsubgrupoproduto={$model->codsubgrupoproduto}") }}"><i class="fa fa-plus"></i></a> 
                    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>                
                </div>
            </h4>
            <div class='card-block table-responsive'>       
                @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Produto']])
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>


@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("sub-grupo-produto/$model->codsubgrupoproduto/edit") }}"><i class="fa fa-pencil"></i></a>
    @if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("sub-grupo-produto/$model->codsubgrupoproduto/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->subgrupoproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("sub-grupo-produto/$model->codsubgrupoproduto/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->subgrupoproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("sub-grupo-produto/$model->codsubgrupoproduto") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->subgrupoproduto }}'?" data-after="location.replace('{{ url("grupo-produto/{$model->codgrupoproduto}") }}');"><i class="fa fa-trash"></i></a>
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection


@section('inscript')
    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('produto/datatable'), 'order' => 3, 'order_dir' => 'ASC', 'filtros' => ['codsubgrupoproduto', 'codproduto' => 'codproduto', 'produto', 'inativo'] ])
@endsection
@stop