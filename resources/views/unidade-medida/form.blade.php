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
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
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