@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h3 class="card-header">Pesquisa</h3>
    <div class="card-block">
        <div class="card-text">
            <form accept-charset="UTF-8" class="form-horizontal" id="form-search" role="search" autocomplete="on">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codunidademedida" class="control-label">#</label>
                        <input class="form-control" placeholder="#" name="codunidademedida" type="number" step="1" min="1" id="codunidademedida">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="unidademedida" class="control-label">Unidade Medida</label>
                        <input class="form-control" placeholder="Unidade Medida" name="unidademedida" type="text" id="unidademedida">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="sigla" class="control-label">Sigla</label>
                        <input class="form-control" placeholder="Sigla" name="sigla" type="text" id="sigla">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="inativo" class="control-label">Excluídos</label>
                        {!! Form::select2Ativo('inativo') !!}
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>    
        </div>
    </div>
  </div>
</div>

<div class="card-box table-responsive">

    <div class="btn-group pull-right" role="group" aria-label="Controles">
        <a class="btn btn-secondary" href="{{ url("unidade-medida/create") }}"><i class="fa fa-plus"></i></a> 
        <a class="btn btn-secondary" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    </div>    
    
    @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => [ '# ALT', 'Unidade Medida', 'Sigla', 'Criação', 'Alteração']])
    
</div>

@section('inscript')

@include('layouts.includes.datatable.assets')

@include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('unidade-medida/datatable')])

<script type="text/javascript">
    $(document).ready(function () {
        
        $('#codunidademedida').change(function() {
            $('#datatable').DataTable().column(0).search(
                $('#codunidademedida').val(),
                false,
                true
            ).draw();
        });
        
        $('#unidademedida').change(function() {
            $('#datatable').DataTable().column(1).search(
                $('#unidademedida').val(),
                false,
                true
            ).draw();
        });
        
        $('#sigla').change(function() {
            $('#datatable').DataTable().column(2).search(
                $('#sigla').val(),
                false,
                true
            ).draw();
        });
        
        $('#inativo').change(function() {
            console.log($('#inativo').val());
            /*
            $('#datatable').DataTable().column(2).search(
                $('#sigla').val(),
                false,
                true
            ).draw();
            */
        });
    });

</script>
@endsection
@stop