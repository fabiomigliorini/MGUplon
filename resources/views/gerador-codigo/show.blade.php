
<div class="card">
    <h4 class="card-header" id='gerador-titulo'>{{ $tabela }}</h4>
    <div class="card-block" id='gerador-conteudo'>
      <div class='row'>
        <div class='col-md-3'>
          <label for='model'>
              Model
          </label>
          <input type='text' name='model' id='model' value='{{ $model }}' class='form-control' required='required'>
        </div>
        <div class='col-md-3'>
          <label for='titulo'>
              Título
          </label>
          <input type='text' name='titulo' id='titulo' class='form-control' required='required'>
        </div>
        <div class='col-md-6'>
            <label for="tab-arquivos">
                Arquivos
            </label>
            <ul class="nav nav-pills" role="tablist" id="tab-arquivos">
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-model" role="tab">Model</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-repository" role="tab">Repository</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-policy" role="tab">Policy</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-controller" role="tab">Controller</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-view" role="tab">View</a>
              </li>
            </ul>
        </div>
      </div>
      <br>
      <!-- Nav tabs -->
    </div>
</div>


<!-- Tab panes -->
<div class="tab-content" id="resultados" style="display:none">
  <div class="tab-pane" id="tab-model" role="tabpanel"></div>
  <div class="tab-pane" id="tab-repository" role="tabpanel"></div>
  <div class="tab-pane" id="tab-policy" role="tabpanel"></div>
  <div class="tab-pane" id="tab-controller" role="tabpanel"></div>
  <div class="tab-pane" id="tab-view" role="tabpanel"></div>
</div>        

<script type="text/javascript">
    
    function escondeResultados() {
        $('#resultados').fadeOut();
    }
    
    function mostraResultados() {
        $('#resultados').fadeIn();
    }
    
    function abreModel() {
        var model = $('#model').val();
        var titulo = $('#titulo').val();
        
        $.get('{{ url("gerador-codigo/$tabela/model") }}', {
            model: model,
            titulo: titulo,
        }).done(function(data) {
            $('#tab-model').html(data);
            mostraResultados();
        });
    }
    
    function abreRepository() {
        var model = $('#model').val();
        var titulo = $('#titulo').val();
        
        $.get('{{ url("gerador-codigo/$tabela/repository") }}', {
            model: model,
            titulo: titulo,
        }).done(function(data) {
            $('#tab-repository').html(data);
            mostraResultados();
        });
    }
    
    function abrePolicy() {
        var model = $('#model').val();
        var titulo = $('#titulo').val();
        
        $.get('{{ url("gerador-codigo/$tabela/policy") }}', {
            model: model,
            titulo: titulo,
        }).done(function(data) {
            $('#tab-policy').html(data);
            mostraResultados();
        });
    }
    
    function geraTitulo() {
        var s = $('#model').val();
        s = s.replace(/([A-Z])/g, ' $1').trim()
        $('#titulo').val(s);
    }

    $(document).ready(function () {
        geraTitulo();
        $('#model').focus();
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            switch ($(e.target).attr('href')) {
                case '#tab-model':
                    abreModel();
                    break;
                case '#tab-repository':
                    abreRepository();
                    break;
                case '#tab-policy':
                    abrePolicy();
                    break;
            }
        });
        
        $('#model').keyup(function () {
            escondeResultados();
            geraTitulo();
        });
        
        $('#titulo').keyup(function () {
            escondeResultados();
        });
    });
</script>
