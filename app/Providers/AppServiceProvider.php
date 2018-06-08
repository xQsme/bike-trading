<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Subcategoria;
use App\Item;
use App\User;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $subcategorias = Subcategoria::all();
        $anuncios = Item::where('aprovado', 1)->count();
        $users = User::where('verified', 1)->count();
        $data = array(
            'subcategorias' => $subcategorias,
            'anuncios' => $anuncios,
            'users' => $users,
        );
        View::share('data', $data);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
