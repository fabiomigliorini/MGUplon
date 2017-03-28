<fieldset class="form-group">
    {!! Form::label('arquivo', 'Arquivo') !!}
    <!--{!! Form::file('codimagem', null, ['class'=> 'form-control', 'id'=>'codimagem', 'accept'=>'image/*']) !!}-->
    <input type="file" name="codimagem" id="codimagem" accept="image/*" required="">
</fieldset>
<fieldset class="form-group">
    {!! Form::label('observacoes', 'Observacoes') !!}
    {!! Form::text('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'maxlength'=>'200', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#form-principal').on("submit", function(e) {
        e.preventDefault();
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja salvar?",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            currentForm.submit();
          }
        });
    });
    $("#observacoes").Setcase();
    $("#observacoes").maxlength({alwaysShow: true});
    $("#arquivo").Setcase();
    $("#arquivo").maxlength({alwaysShow: true});
});
</script>
@endsection