<?php
    //...
?>
<fieldset class="form-group">
    {!! Form::label('unidademedida', 'Descrição :') !!}
    {!! Form::text('unidademedida', null, ['class'=> 'form-control', 'id'=>'unidademedida', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('sigla', 'Sigla:') !!}
    {!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
   {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#form-unidade-medida').on("submit", function(e) {
        console.log('aqui');
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });    
});
</script>
@endsection