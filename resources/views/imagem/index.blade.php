@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codimagem" class="control-label">#</label>
                        {!! Form::number('codimagem', null, ['class'=> 'form-control', 'id'=>'codimagem', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codimagem" class="control-label">Imagem</label>
                        {!! Form::text('codimagem', null, ['class'=> 'form-control', 'id'=>'codimagem']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="observacoes" class="control-label">Observacoes</label>
                        {!! Form::text('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="arquivo" class="control-label">Arquivo</label>
                        {!! Form::text('arquivo', null, ['class'=> 'form-control', 'id'=>'arquivo']) !!}
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
    <div class='card-block table-responsive'>
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Imagem', 'Observacoes', 'Arquivo', ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("imagem/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('imagem/datatable'), 'order' => $filtro['order'], 'filtros' => ['codimagem', 'codimagem', 'inativo', 'observacoes', 'arquivo', ] ])

    <script type="text/javascript">
        $(document).ready(function () {
            datable_datatable.columnDefs {
                render: function ( data, type, row ) {
                        return '<strong>' + data +'</strong>';
                },
                targets: 3
            };
        });
    </script>

@endsection
@stop
