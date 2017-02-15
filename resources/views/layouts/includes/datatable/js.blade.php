<?php
$page_length = $page_length??100;
?>
<script type="text/javascript">
    $(document).ready(function () {
        
        var table = $('#{{ $id }}').DataTable({
            dom: 'Brtip',
            pageLength: {{ $page_length }},
            language: {
                url: "{{ URL::asset('public/assets/plugins/datatables/Portuguese-Brasil.lang') }}"
            },
            processing: true,
            serverSide: true,
            ajax: '{{ $url }}',
            lengthChange: false,
            buttons: [
                { extend: 'copy', text: 'Copiar' },
                'excel', 
                'pdf', 
                { extend: 'print', text: 'Imprimir' },
                { extend: 'colvis', text: 'Colunas' }
            ],
            initComplete: function(settings, json) {
                table.buttons().container().appendTo('#{{ $id }}_wrapper .col-md-6:eq(0)');
                $('#{{ $id }}_paginate, #{{ $id }}_info').addClass('col-md-6');
            }             
        });
        
    });

</script>
