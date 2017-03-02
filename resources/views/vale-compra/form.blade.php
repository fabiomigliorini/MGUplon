<div class='row'>
    {!! Form::hidden('codvalecompramodelo', null, ['class'=> 'form-control', 'id'=>'codvalecompramodelo', 'required'=>'required']) !!}
    {!! Form::hidden('codpessoafavorecido', null, ['class'=> 'form-control', 'id'=>'codpessoafavorecido', 'required'=>'required']) !!}
    <div class='col-md-6'>
        <fieldset class="form-group">
            {!! Form::label('aluno', 'Aluno') !!}
            {!! Form::text('aluno', null, ['class'=> 'form-control', 'id'=>'aluno', 'required'=>'required']) !!}
        </fieldset>
        <fieldset class="form-group">
            {!! Form::label('codpessoa', 'Cliente') !!}
            {!! Form::select2Pessoa('codpessoa', null, ['class'=> 'form-control', 'id'=>'codpessoa', 'placeholder'=>'Cliente', 'required'=>'required']) !!}
        </fieldset>
        <fieldset class="form-group">
            {!! Form::label('turma', 'Turma') !!}
            {!! Form::text('turma', null, ['class'=> 'form-control', 'id'=>'turma', 'required'=>'required']) !!}
        </fieldset>
        <fieldset class="form-group">
            {!! Form::label('barras', 'Produto') !!}
            <div class="row">
                <div class="col-md-4">
                {!! Form::number('quantidade', 1, ['class'=> 'form-control text-right', 'id'=>'quantidade', 'tabindex'=>-1, 'step'=>0.001, 'min'=>0.001]) !!}
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        {!! Form::text('barras', null, ['class'=> 'form-control text-center', 'id'=>'barras']) !!}
                        <div class="input-group-btn">
                          <button class='btn btn-primary' id='btnAdicionarProdutoBarra' tabindex="-1"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <div class='col-md-6'>
        <fieldset class="form-group">
            {!! Form::label('codfilial', 'Filial') !!}
            {!! Form::select2Filial('codfilial', Auth::user()->codfilial, ['class'=> 'form-control', 'id'=>'codfilial', 'required'=>'required']) !!}
        </fieldset>
        <fieldset class="form-group">
            {!! Form::label('observacoes', 'Observacoes') !!}
            {!! Form::textarea('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'rows' => 3, 'tabindex'=>-1]) !!}
        </fieldset>
        <fieldset class="form-group">
            {!! Form::label('codprodutobarra_pesquisa', 'Pesquisa') !!}
            {!! Form::select2ProdutoBarra('codprodutobarra_pesquisa', null, ['class'=> 'form-control', 'id'=>'codprodutobarra_pesquisa']) !!}
        </fieldset>        
    </div>
</div>

<br>
<ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
  @foreach ($prods as $vcmpb)
    <li class='list-group-item linha_produto'>
        <div class="row">
          {!! Form::hidden('item_codprodutobarra[]', $vcmpb->codprodutobarra, ['class'=> 'form-control item_codprodutobarra']) !!}
          {!! Form::hidden('item_codvalecomprabarra[]', $vcmpb->codvalecomprabarra, ['class'=> 'form-control item_codprodutobarra']) !!}
          <div class='col-md-6'>
            <div class='col-md-3'>
              <span class='item_barras'>
                {{ $vcmpb->ProdutoBarra->barras }}
              </span>
            </div>
            <a href='{{ url('produto', $vcmpb->ProdutoBarra->codproduto) }}' class='item_link_produto'>
              <span class='item_produto'>
                {{ $vcmpb->ProdutoBarra->descricao() }}
              </span>
            </a>

            <?php $inativo = $vcmpb->ProdutoBarra->Produto->inativo; ?>
            @if (!empty($inativo))
              <span class='text-danger item_inativo'>
                Inativo
              </span>
            @endif
            
          </div>
          <div class='col-md-6'>
            <div class='col-md-4'>
                <div class="input-group">
                    {!! Form::number('item_quantidade[]', $vcmpb->quantidade, ['class'=> 'form-control text-right item_quantidade', 'min'=>0.000, 'step'=>0.001]) !!}
                    <div class="input-group-addon item_unidademedida">
                      {{ $vcmpb->ProdutoBarra->UnidadeMedida->sigla }}
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
              {!! Form::number('item_preco[]', $vcmpb->preco, ['class'=> 'form-control text-right item_preco', 'min'=>0.00, 'step'=>0.01]) !!}
            </div>
            <div class='col-md-4'>
              {!! Form::number('item_total[]', $vcmpb->total, ['class'=> 'form-control text-right item_total', 'min'=>0.00, 'step'=>0.01]) !!}
            </div>
            <div class='col-md-1 text-right'>
              <a href='#' class='item_delete'>
                <i class="fa fa-trash"></i>
              </a>
            </div>
          </div>
        </div>
    </li>
  @endforeach
  
    <!-- Linha de modelo que o Jquery vai utilizar -->
    <li class='list-group-item linha_produto hidden'>
        <div class="row">
          {!! Form::hidden('item_codprodutobarra[]', null, ['class'=> 'form-control item_codprodutobarra']) !!}
          <div class='col-md-6'>
            <div class='col-md-3'>
              <span class='item_barras'>
              </span>
            </div>
            <a href='' class='item_link_produto'>
              <span class='item_produto'>
              </span>
            </a>
            <span class='text-danger item_inativo'>
                Inativo
            </span>
          </div>
          <div class='col-md-6'>
            <div class='col-md-4'>
                <div class="input-group">
                    {!! Form::number('item_quantidade[]', 1, ['class'=> 'form-control text-right item_quantidade', 'min'=>0.000, 'step'=>0.001]) !!}
                    <div class="input-group-addon item_unidademedida">
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
              {!! Form::number('item_preco[]', null, ['class'=> 'form-control text-right item_preco', 'min'=>0.00, 'step'=>0.01]) !!}
            </div>
            <div class='col-md-4'>
              {!! Form::number('item_total[]', null, ['class'=> 'form-control text-right item_total', 'min'=>0.00, 'step'=>0.01]) !!}
            </div>
            <div class='col-md-1 text-right'>
              <a href='#' class='item_delete'>
                <i class="fa fa-trash"></i>
              </a>
            </div>
          </div>
        </div>
    </li>
  
  <li class='list-group-item'>
    <div class="row">
      <div class='col-md-offset-6 col-md-6'>
        {!! Form::label('totalprodutos', 'Produtos:', ['class'=>'col-sm-6 control-label']) !!}
        <div class="col-sm-5">{!! Form::number('totalprodutos', null, ['class'=> 'form-control text-right', 'disabled'=>'disabled', 'id'=>'totalprodutos']) !!}</div>
      </div>
    </div>
  </li>
  <li class='list-group-item'>
    <div class="row">
      <div class='col-md-offset-6 col-md-6'>
        {!! Form::label('desconto', 'Desconto:', ['class'=>'col-sm-6 control-label']) !!}
        <div class="col-sm-5">
          <div class='input-group'>
            <?php $percentual_desconto = ($model->totalprodutos > 0)?round(($model->desconto / $model->totalprodutos)*100, 2):null ?>
            {!! Form::number('percentual_desconto', $percentual_desconto, ['class'=> 'form-control text-right', 'id'=>'percentual_desconto', 'step'=> 0.01, 'min'=>0.00, 'max'=>99.99]) !!}
            <div class='input-group-addon'>%</div>
          </div>
          {!! Form::number('desconto', null, ['class'=> 'form-control text-right', 'id'=>'desconto', 'step'=> 0.01, 'min'=>0.00]) !!}
        </div>
      </div>
    </div>
  </li>
  <li class='list-group-item'>
    <div class="row">
      <div class='col-md-offset-6 col-md-6'>
        {!! Form::label('total', 'Total:', ['class'=>'col-sm-6 control-label']) !!}
        <div class="col-sm-5">{!! Form::number('total', null, ['class'=> 'form-control text-right', 'disabled'=>'disabled', 'id'=>'total']) !!}</div>
      </div>
    </div>
  </li>
  <li class='list-group-item'>
    <div class="row">
      <div class='col-md-offset-6 col-md-6'>
        {!! Form::label('codformapagamento', 'Forma de Pagamento:', ['class'=>'col-sm-6 control-label']) !!}
        <div class="col-sm-5">{!! Form::select2FormaPagamento('codformapagamento', 1010, ['class'=> 'form-control ', 'id'=>'codformapagamento', 'required'=>true]) !!}</div>
      </div>
    </div>
  </li>
  
</ul>  

<br>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script type="text/javascript">
    
function calculaTotais () {
    var totalprodutos = 0;
    $('.linha_produto').each( function () {
        if ($(this).find(".item_codprodutobarra").first().val() != '') {
            var quant = $(this).find(".item_quantidade").first().val();
            var preco = $(this).find(".item_preco").first().val();
            var total = Math.round(preco * quant * 100) / 100
            var campo_total = $(this).find(".item_total").first();
            campo_total.val(total);
            campo_total.prop('min', 0.01)
            totalprodutos += total;
        }
    });
    totalprodutos = Math.round(totalprodutos * 100)/100
    $('#totalprodutos').val(totalprodutos);
    $('#desconto').prop('max', totalprodutos);
    if (totalprodutos>0) {
        var perc_desc = Math.round(($('#desconto').val() / totalprodutos)*100*100)/100;
        $('#percentual_desconto').val(perc_desc);
    }
    $('#total').val(Math.round((totalprodutos - $('#desconto').val()) * 100)/100);
}

function adicionaProduto(produto) {
    
    console.log(produto);
    
    var div = $('.linha_produto').last().clone(true, true);
    
    if (produto.inativo != null) {
        div.find(".item_inativo").first().removeClass("hidden");
    } else {
        div.find(".item_inativo").first().addClass("hidden");        
    }
    div.find(".item_link_produto").first().attr("href", produto.url);
    div.find(".item_codprodutobarra").first().val(produto.codprodutobarra);
    div.find(".item_barras").first().html(produto.barras);
    div.find(".item_unidademedida").first().html(produto.slglaunidademedida);
    console.log(produto.barras);
    div.find(".item_produto").first().html(produto.produto);
    div.find(".item_preco").first().val(produto.preco);
    div.find(".item_quantidade").first().val($('#quantidade').val());
    $('#quantidade').val(1);
    div.removeClass('hidden');
    
    $("#codprodutobarra_pesquisa").select2("val", null);
    $('#barras').focus();
    
    $('#divListagemProdutos').prepend(div);
    
    calculaTotais();
}

function consultaBarras (barras) {
    
    //http://localhost/MGLara/produto/consulta/010203
    $.ajax({
        type: 'GET',
        url: '{{ url('produto/consulta') }}/' + barras,
        dataType: 'json',
        success: function(retorno) {
            if (retorno.resultado == false) {
                bootbox.alert('<span class="text-danger">' + retorno.mensagem + '</span>');
            } else {
                adicionaProduto(retorno.produto);
            }
        },
        error: function (XHR, textStatus) {
            bootbox.alert('<span class="text-danger">Erro ao adicionar o produto!</span>');
            console.log(XHR);
            console.log(textStatus);
        }
    });    
    
}

function preencheQuantidade()
{
    //pega campo com codigo de barras
    var barras = $("#barras").val().trim();

    //o tamanho com o asterisco deve ser entre 2 e 5
    if (barras.length > 6 || barras.length < 2)
            return;

    // se o último dígito é o asterisco
    if (barras.slice(-1) != "*")
            return;

    //se o valor antes do asterisco é um número
    barras = barras.substr(0, barras.length - 1).trim().replace(',', '.');
    if (!$.isNumeric(barras))
            return;

    // se o número é maior ou igual à 1
    barras=parseFloat(barras);
    if (barras < 0.01)
            return;

    //preenche o campo de quantidade
    $("#quantidade").val(barras);

    //limpa o código de barras
    $("#barras").val("");

    $('#quantidade').animate({opacity: 0.25,}, 200 );			
    $('#quantidade').animate({opacity: 1,}, 200 );			
}

$(document).ready(function() {

    $(':input:enabled:visible:first').focus();
    
    $('#aluno').Setcase();
    $('#turma').Setcase();

    //$('#barras').focus();

    $('#form-vale-compra').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
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
    
    $('#btnAdicionarProdutoBarra').click(function (e) {
        var barras = $('#barras').val();
        if (barras != '') {
            e.preventDefault();
            consultaBarras(barras);
            $('#barras').val('');
        }
    });
    
    $('.item_quantidade, .item_preco, #desconto').change(function (e) {
        calculaTotais();
    });
    
    $('.item_total').change(function (e) {
        var total = $(this).val();
        var quant = $(this).closest('.linha_produto').find(".item_quantidade").first().val();
        $(this).closest('.linha_produto').find(".item_preco").first().val(Math.round((total / quant) * 100) / 100);
        calculaTotais();
    });
    
    $('.item_delete').click(function (e) {
        var linha = $(this).closest('.linha_produto');
        swal({
          title: "Tem Certeza que deseja excluir este item?",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                linha.remove();
                calculaTotais();
            } 
        });        
        
        return false;
    });
    
    $("#codprodutobarra_pesquisa").on("select2-selecting", function(e) { 
        var barras = e.object.barras;
        if (barras != '') {
            consultaBarras(barras);
        }
    });
    
    $(document).on('hidden.bs.modal','.bootbox', function () {
        $('#barras').focus();
    });
    
    $('#percentual_desconto').change(function (e) {
        var desc = Math.round($('#percentual_desconto').val() * $('#totalprodutos').val())/100;
        $('#desconto').val(desc);
        calculaTotais();
    });
    
    $("#barras").keyup(function(){ 
        preencheQuantidade();
    });    
});
</script>
@endsection