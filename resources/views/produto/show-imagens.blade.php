<div id="div-imagens">
    <?php $imagens = $model->ProdutoImagemS()->orderBy('codimagem')->get(); ?>
    <p>
        <a href="{{ url("/imagem/produto/$model->codproduto") }}">
            Nova Imagem
            <i class="fa fa-plus"></i> 
        </a>
    </p>
    <div id="carouselImagens" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
        @for ($i = 0; $i < sizeof($imagens); $i++)
            <li data-target="#carouselImagens" data-slide-to="{{ $i }}" class="{{ ($i == 0)?'active':'' }}"></li>
        @endfor            
        </ol>    
        <div class="carousel-inner" role="listbox">
        @if (sizeof($imagens) == 0)
            <div class="carousel-item text-center {{ ($i == 0)?'active':'' }}">
                <img class="d-block img-fluid" src="{{ URL::asset('public/imagens/semimagem.jpg') }}" style='margin: 0 auto;'>
            </div>
        @endif        
        @foreach($imagens as $i => $imagem)
            <div class="carousel-item {{ ($i == 0)?'active':'' }}">
                <a href='{{ URL::asset('public/imagens/'.$imagem->observacoes) }}' target="_blank">
                    <img class="d-block img-fluid" src="{{ URL::asset('public/imagens/'.$imagem->observacoes) }}" >
                </a>
                <div class="carousel-caption">
                    <p>{{ $imagem->observacoes }}</p>
                    <p>
                        <a href='{{ url("imagem/produto/$model->codproduto?imagem={$imagem->codimagem}") }}'><i class="text-white fa fa-pencil"></i></a>
                        <a href="{{ url("imagem/produto/{$model->codproduto}/delete?imagem={$imagem->codimagem}") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a imagem '{{ $imagem->observacoes }}'?" data-after-delete="recarregaDiv('div-imagens');"><i class="text-white fa fa-trash"></i></a>
                    </p>
                </div>                
            </div>
        @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselImagens" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselImagens" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>   
</div>