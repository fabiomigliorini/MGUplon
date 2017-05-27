<div id="div-imagens">
    <?php $imagens = $model->ImagemS()->orderBy('codimagem')->get(); ?>
    <p>
        <a href="{{ url("/imagem/create?model=produto&id=$model->codproduto") }}" title="Cadastrar imagem">
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
                <a href="{{ URL::asset("public/imagens/$imagem->arquivo") }}" target="_blank">
                    <img class="d-block img-fluid" src="{{ URL::asset('public/imagens/'.$imagem->arquivo) }}" >
                </a>
                <div class="carousel-caption">
                    <p>{{ $imagem->arquivo }}</p>
                    <p>
                        <a href="{{ url("/imagem/create?model=produto&id=$model->codproduto&codimagem=$imagem->codimagem") }}"><i class="text-white fa fa-pencil"></i></a>
                        <a data-codimagem="{{ $imagem->codimagem }}"  id="delete-imagem"><i class="fa fa-trash"></i></a>
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