<?php

namespace MGLara\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \MGLara\Models\GrupoUsuario::class      => \MGLara\Policies\GrupoUsuarioPolicy::class,
        \MGLara\Models\Marca::class             => \MGLara\Policies\MarcaPolicy::class,
        \MGLara\Models\Permissao::class         => \MGLara\Policies\PermissaoPolicy::class,
        \MGLara\Models\UnidadeMedida::class     => \MGLara\Policies\UnidadeMedidaPolicy::class,
        \MGLara\Models\Usuario::class           => \MGLara\Policies\UsuarioPolicy::class,
        \MGLara\Models\SecaoProduto::class      => \MGLara\Policies\SecaoProdutoPolicy::class,
        \MGLara\Models\FamiliaProduto::class    => \MGLara\Policies\FamiliaProdutoPolicy::class,
        \MGLara\Models\GrupoProduto::class      => \MGLara\Policies\GrupoProdutoPolicy::class,
        \MGLara\Models\SubGrupoProduto::class   => \MGLara\Policies\SubGrupoProdutoPolicy::class,
        \MGLara\Models\TipoProduto::class       => \MGLara\Policies\TipoProdutoPolicy::class,
        \MGLara\Models\Banco::class             => \MGLara\Policies\BancoPolicy::class,
        \MGLara\Models\Pais::class              => \MGLara\Policies\PaisPolicy::class,
        \MGLara\Models\EstadoCivil::class       => \MGLara\Policies\EstadoCivilPolicy::class,        
        \MGLara\Models\ValeCompra::class        => \MGLara\Policies\ValeCompraPolicy::class,
        \MGLara\Models\Feriado::class           => \MGLara\Policies\FeriadoPolicy::class,
        \MGLara\Models\ValeCompraModelo::class  => \MGLara\Policies\ValeCompraModeloPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
    
    public function getPolicies($model = null) 
    {
        if (!empty($model)) {
            if (isset($this->policies[$model])) {
                return $this->policies[$model];
            } else {
                return false;
            }
        }
        return $this->policies;
    }
}
