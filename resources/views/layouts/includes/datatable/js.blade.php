<?php
$page_length = $page_length??100;
$order = $order??'2';
$order_dir = $order_dir??'ASC';
$link_column = $link_column??2;

?>
<script type="text/javascript">
$(document).ready(function () {

    var datable_{{ $id }} = $('#{{ $id }}').DataTable({
        dom: 'Brtip',
        pageLength: {{ $page_length }},
        language: {
            url: "{{ URL::asset('public/assets/plugins/datatables/Portuguese-Brasil.lang') }}"
        },
        processing: true,
        serverSide: true,
        order: [[ {{ $order }}, "{{ $order_dir }}" ]],
        ajax: {
            url: '{{ $url }}',
            data: function ( d ) {
                d.filtros = new Object;
                @foreach ($filtros as $campo => $filtro)
                    d.filtros.{{ $filtro }} = $('#{{ (is_numeric($campo)?$filtro:$campo) }}').val();
                @endforeach
            }
        },
        lengthChange: false,
        buttons: [
            { extend: 'copy', text: 'Copiar', exportOptions: { columns: ':visible' } },
            { extend: 'excel', text: 'Excel', exportOptions: { columns: ':visible' } },
            { extend: 'pdf', text: 'PDF', exportOptions: { columns: ':visible' } },
            { extend: 'print', text: 'Imprimir', exportOptions: { columns: ':visible' } },
            { extend: 'colvis', text: 'Colunas', exportOptions: { columns: ':visible' } },
        ],
        columnDefs: [
            {
                targets: [0, 1],
                visible: false,
            },
            {
                render: function ( data, type, row ) {
                    return '<a href="' + row[0] + '">' + data +'</a>';
                },
                targets: {{ $link_column }}
            }
        ],
        initComplete: function(settings, json) {
            datable_{{ $id }}.buttons().container().appendTo('#{{ $id }}_wrapper .col-md-6:eq(0)');
            $('#{{ $id }}_paginate, #{{ $id }}_info').addClass('col-md-6');
        },
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (aData[1] != null) {
                $(nRow).addClass('table-danger');
            }
        }
    });

    @foreach ($filtros as $campo => $filtro)
        $('#{{ (is_numeric($campo)?$filtro:$campo) }}').change(function() {
            datable_{{ $id }}.ajax.reload();
        });
    @endforeach




});

</script>
