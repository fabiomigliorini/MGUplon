@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">
                Trocar senha
            </h4>
            <div class="card-block">
            <form>
                @include('errors.form_error')
                <fieldset class="form-group">
                    {!! Form::label('senha', 'Nova senha') !!}
                    {!! Form::password('senha', ['id'=>'senha', 'required'=>'required', 'class'=>'form-control', 'minlength'=>'4']) !!}
                </fieldset>
            
                <fieldset class="form-group">
                    {!! Form::label('repetir_senha', 'Confirmação') !!}
                    {!! Form::password('repetir_senha', ['id'=>'repetir_senha', 'required'=>'required', 'class'=>'form-control', 'minlength'=>'4']) !!}
                    <span id="error-rpt"></span>
                </fieldset>
            
                <fieldset class="form-group">
                   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
                </fieldset>    
            </form>
            </div>
        </div>
    </div>
</div>
@section('inscript')

<style type="text/css">
    #error-rpt {
        float:right;
        color: #b94a48;
    }
</style>
<script type="text/javascript">
$(document).ready(function() {
    function validarSenha(form){ 
        senha = $('#senha').val();
        senhaRepetida = $('#repetir_senha').val();
        if (senha != senhaRepetida){
            var aviso = '<span id="rpt">Confirmação deve ser exatamente repetido</span>';
            var destino = $('#error-rpt');
            destino.append(aviso);
            setTimeout(function(){
                $('#rpt').empty();
            },13000);            
            $('repetir_senha').focus();
            return false;
        } else {
            $("#rpt").empty();
        }
    }    
    
    $("#repetir_senha" ).blur(function() {
      validarSenha();
    });    
    
    $('#form-usuario').on("submit", function(e) {
        if(validarSenha() === false) {
            return false;
        }

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
    
    $('form').on('submit', function(e){
        e.preventDefault();
        mudarSenha();
    });
    
    function mudarSenha() {
        var data = $('form').serialize();
        $.ajax({
            type: 'POST',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ url("usuario/mudar-senha") }}',
            dataType: 'json',
            data: data,
            success: function(data) {
                toastr['success']('Senha alterada com sucesso!');
            },
            error: function(XHR) {
                toastr['error'](XHR.status + ' ' + XHR.statusText);
            },
        });
    }    

});
</script>
@endsection
@stop

