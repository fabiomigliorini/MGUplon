@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro, ['id' => 'form-search', 'autocomplete' => 'on'])!!}
            <div class="col-md-2">
                <div class="form-group">
                    <label for="inativo" class="control-label">Ativos</label>
                    {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                </div>
            </div>
            <div class="clearfix"></div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
  </div>
</div>

<!--<div class='card'>-->
    <!--<div class='card-block table-responsive'>-->
        <div class="row m-b-20" id="imagens">
            @foreach($model as $row)
            <div class="col-xs-6 col-md-2">
                <div class="thumb">
                    <a href="{{ url('imagem',$row->codimagem) }}" class="image-popup" title="{{ $row->arquivo }}">
                        <img src="{{ URL::asset("public/imagens/$row->arquivo$row->observacoes") }}" class="thumb-img" alt="{{ $row->arquivo }}">
                    </a>
                    
                    <div class="gal-detail text-xs-center">
                        <p class="text-muted">
                            {{ $row->arquivo }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-md-12">{!! $model->appends($filtro)->render() !!}</div>
        </div>
        
        <div class='clearfix'></div>
    <!--</div>-->
<!--</div>-->

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("imagem/lixeira") }}"><i class="fa fa-trash"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')
<script type="text/javascript">
    function atualizaFiltro(pagina)
    {
        if(pagina === null){
            pagina = 1;
        }
        var frmValues = $('#form-search').serialize()+'&page='+pagina;
        console.log(frmValues);
        $.ajax({
            type: 'GET',
            url: baseUrl + '/imagem',
            data: frmValues,
            dataType: 'html'
        })
        .done(function (data) {
            $('#imagens').html(jQuery(data).find('#imagens').html());
            cssPaginacao();
        })
        .fail(function () {
            console.log('Erro no filtro');
        });
    }
    
    function cssPaginacao()
    {
        $("#imagens .pagination li").addClass('page-item');
        $("#imagens .pagination li a,.pagination li span").addClass('page-link');
    }
    
    $(document).ready(function() {
        cssPaginacao();
        $("#form-search").on("change", function (event) {
            atualizaFiltro();
        }).on('submit', function (event){
            event.preventDefault();
            atualizaFiltro();
        });
        
        $(document).on('click', '.pagination a', function (e) {
            atualizaFiltro($(this).attr('href').split('page=')[1]);
            $("#imagens .pagination .active").removeClass('active');
            $(this).addClass('page-link');
            $(this).parent().addClass('active');
            e.preventDefault();
        });        
    });  
</script>
@endsection
@stop
