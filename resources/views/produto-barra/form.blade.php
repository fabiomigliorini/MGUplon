<fieldset class="form-group">
    {!! Form::label('codproduto', 'Codproduto') !!}
    {!! Form::number('codproduto', null, ['class'=> 'form-control', 'id'=>'codproduto', 'step'=>'1', 'min'=>'1', 'required'=>'required', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('variacao', 'Variacao') !!}
    {!! Form::text('variacao', null, ['class'=> 'form-control', 'id'=>'variacao', 'maxlength'=>'100']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('barras', 'Barras') !!}
    {!! Form::text('barras', null, ['class'=> 'form-control', 'id'=>'barras', 'maxlength'=>'50', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('referencia', 'Referencia') !!}
    {!! Form::text('referencia', null, ['class'=> 'form-control', 'id'=>'referencia', 'maxlength'=>'50']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('codmarca', 'Codmarca') !!}
    {!! Form::number('codmarca', null, ['class'=> 'form-control', 'id'=>'codmarca', 'step'=>'1', 'min'=>'1']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('codprodutoembalagem', 'Codprodutoembalagem') !!}
    {!! Form::number('codprodutoembalagem', null, ['class'=> 'form-control', 'id'=>'codprodutoembalagem', 'step'=>'1', 'min'=>'1']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('codprodutovariacao', 'Codprodutovariacao') !!}
    {!! Form::number('codprodutovariacao', null, ['class'=> 'form-control', 'id'=>'codprodutovariacao', 'step'=>'1', 'min'=>'1', 'required'=>'required']) !!}
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
    $("#variacao").Setcase();
    $("#variacao").maxlength({alwaysShow: true});
    $("#barras").Setcase();
    $("#barras").maxlength({alwaysShow: true});
    $("#referencia").Setcase();
    $("#referencia").maxlength({alwaysShow: true});
});
</script>
@endsection