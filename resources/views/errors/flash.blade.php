@if (Session::has('flash_danger'))
    <div class="flash alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_danger') }}
        @if (Session::has('flash_danger_detail'))
            <a href="#" type="button" data-toggle="popover" id="popover_alert_danger_detail" title="Detalhes" data-content="{{ Session::get('flash_danger_detail') }}">Detalhes</a>
            <script>
                $(function () {
                    $('#popover_alert_danger_detail').popover()
                })
            </script>        
        @endif
    </div>
@endif

@if (Session::has('flash_success'))
    <div class="flash alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_success') }}
        @if (Session::has('flash_success_detail'))
            <a href="#" type="button" data-toggle="popover" id="popover_alert_success_detail" title="Detalhes" data-content="{{ Session::get('flash_success_detail') }}">Detalhes</a>
            <script>
                $(function () {
                    $('#popover_alert_success_detail').popover()
                })
            </script>        
        @endif
    </div>
@endif

@if (Session::has('flash_warning'))
    <div class="flash alert alert-warning">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_warning') }}
        @if (Session::has('flash_warning_detail'))
            <a href="#" type="button" data-toggle="popover" id="popover_alert_warning_detail" title="Detalhes" data-content="{{ Session::get('flash_warning_detail') }}">Detalhes</a>
            <script>
                $(function () {
                    $('#popover_alert_warning_detail').popover()
                })
            </script>        
        @endif
    </div>
@endif



@if (Session::has('flash_delete'))
    <div class="flash alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_delete') }}
    </div>
@endif

    
<script type="text/javascript">
    $(document).ready(function() {
        toastr.options = {
          "positionClass": "toast-bottom-right"
        };    
        
        @if (Session::has('flash_update'))
            toastr.success('{{ Session::get('flash_update') }}');
        @endif

        @if (Session::has('flash_create'))
            toastr.success('{{ Session::get('flash_create') }}');
        @endif
        
    });
</script>

