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
        \MGLara\Models\GrupoUsuario::class => \MGLara\Policies\GrupoUsuarioPolicy::class,
        \MGLara\Models\Marca::class => \MGLara\Policies\MarcaPolicy::class,
        \MGLara\Models\Permissao::class => \MGLara\Policies\PermissaoPolicy::class,
        \MGLara\Models\UnidadeMedida::class => \MGLara\Policies\UnidadeMedidaPolicy::class,
        \MGLara\Models\Usuario::class => \MGLara\Policies\UsuarioPolicy::class,
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
}
