@extends('layouts.default')
@section('content')
<div class='collapse' id="collapseExample">
<div class="card-box" >
<div class='row'>
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
        <div class="clearfix"></div>
    </form>
      </div>
      
  
</div>
</div>
<div class="card-box">
 
    <a class="pull-right btn btn-primary " data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <i class='fa fa-search'></i>
      </a>

    
    <table id="datatable-listagem" class="display table table-hover table-bordered table-striped table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Unidade Medida</th>
                <th>Sigla</th>
                <th>Unidade Medida</th>
                <th>Unidade Medida</th>
                <th>Unidade Medida</th>
                <th>Unidade Medida</th>
                <th>Unidade Medida</th>
                <th>Unidade Medida</th>
            </tr>
        </thead>
    </table>
</div>
@section('inscript')
<link href="{{ URL::asset('public/assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>

<!-- Required datatable js -->
<script src="{{ URL::asset('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Buttons examples -->
<script src="{{ URL::asset('public/assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/buttons.colVis.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ URL::asset('public/assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        
        var table = $('#datatable-listagem').DataTable({
            //dom: 'Bfrtip',
            dom: 'rtipB',
            pageLength: 100,
            language: {
                url: "{{ URL::asset('public/assets/plugins/datatables/Portuguese-Brasil.lang') }}"
            },
            processing: true,
            serverSide: true,
            ajax: '{{ url('unidade-medida/datatable-listagem') }}',
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
            /*
            columnDefs: [
                {
                    // The `data` parameter refers to the data for the cell (defined by the
                    // `data` option, which defaults to the column being worked with, in
                    // this case `data: 0`.
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('unidade-medida') }}/' + row[0] + '">' + data +'</a>';
                    },
                    "targets": 1
                },
                { "visible": false,  "targets": [ 0 ] }
            ],      
            */
        });
        
        //table.buttons().container().appendTo('#botoes');
        
        $('#codunidademedida').change(function() {
            $('#datatable-listagem').DataTable().column(0).search(
                $('#codunidademedida').val(),
                false,
                true
            ).draw();
        });
        
        $('#unidademedida').change(function() {
            $('#datatable-listagem').DataTable().column(1).search(
                $('#unidademedida').val(),
                false,
                true
            ).draw();
        });
        
        $('#sigla').change(function() {
            $('#datatable-listagem').DataTable().column(2).search(
                $('#sigla').val(),
                false,
                true
            ).draw();
        });
    });

</script>
@endsection
@stop