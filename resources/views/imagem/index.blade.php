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
        <div class="row m-b-20">
            @foreach($model as $row)
            <div class="col-xs-6 col-md-2">
                <div class="thumb">
                    <a href="{{ URL::asset("public/imagens/$row->arquivo$row->observacoes") }}" class="image-popup" title="{{ $row->arquivo }}">
                        <img src="{{ URL::asset("public/imagens/$row->arquivo$row->observacoes") }}" class="thumb-img" alt="{{ $row->arquivo }}">
                    </a>
                    
                    <div class="gal-detail text-xs-center">
                        <p class="text-muted">
                            {{ $row->arquivo }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
            {!! $model->appends($filtro)->render() !!}
        </div>




        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("imagem/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    <script type="text/javascript">
        $(document).ready(function () {
            // ...
        });
    </script>

@endsection
@stop
