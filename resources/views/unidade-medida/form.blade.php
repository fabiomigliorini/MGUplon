<?php
    //...
?>
<fieldset class="form-group">
    {!! Form::label('unidademedida', 'Descrição') !!}
    {!! Form::text('unidademedida', null, ['class'=> 'form-control', 'id'=>'unidademedida', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('sigla', 'Sigla') !!}
    {!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
   {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<!-- Sweet Alert css -->
<link href="{{ URL::asset('public/assets/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css" />
<!-- Switchery css -->
<link href="{{ URL::asset('public/assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />
<!-- Sweet Alert js -->
<script src="{{ URL::asset('public/assets/plugins/bootstrap-sweetalert/sweet-alert.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#form-unidade-medida').on("submit", function(e) {
        e.preventDefault();
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja salvar?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            currentForm.submit();
          } 
        });       
    });  
});
</script>
@endsection