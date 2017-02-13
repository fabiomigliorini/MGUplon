@extends('layouts.default')
@section('content')
<div class="card-box table-responsive">
    <table id="datatable-listagem" class="display table table-hover table-bordered table-striped " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Unidade Medida</th>
                <th>Sigla</th>
            </tr>
        </thead>
    </table>
    <div class="botoes"></div>
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
            dom: 'Bfrtip',
            language: {
                url: "{{ URL::asset('public/assets/plugins/datatables/Portuguese-Brasil.lang') }}"
            },
            processing: true,
            serverSide: true,
            ajax: '{{ url('unidade-medida/datatable-listagem') }}',
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });
        //table.buttons().container()
            //.appendTo('.botoes');        
            //.appendTo('#datatable-buttons_wrapper .row .col-md-6:eq(0)');        

    });

</script>
@endsection
@stop