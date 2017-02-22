@extends('layouts.index')
@section('filter')

    {!! Form::model($filtro, ['id' => 'form-search', 'autocomplete' => 'on'])!!}
        <div class="col-md-2">
            <div class="form-group">
                <label for="codunidademedida" class="control-label">#</label>
                {!! Form::number('codunidademedida', null, ['class'=> 'form-control', 'id'=>'codunidademedida', 'step'=>1, 'min'=>1]) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="unidademedida" class="control-label">Unidade Medida</label>
                {!! Form::text('unidademedida', null, ['class'=> 'form-control', 'id'=>'unidademedida']) !!}
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="sigla" class="control-label">Sigla</label>
                {!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla']) !!}
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

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("unidade-medida/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('datatable')

    @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Unidade Medida', 'Sigla', 'Criação', 'Alteração']])
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('unidade-medida/datatable'), 'order' => 3, 'order_dir' => 'ASC', 'filtros' => ['codunidademedida' => 'codunidademedida', 'unidademedida', 'sigla', 'inativo'] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop