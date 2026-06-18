<?php

namespace App\Providers;

use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            if (Auth::check() && Auth::user()->role == 'siswa') {
                $siswa = Siswa::where('user_id', auth()->id())->first();
                $id_eskul = json_decode($siswa->ekstrakurikuler_id, true);
                $eskul = Ekstrakurikuler::whereIn('id', $id_eskul)->get();
                // dd($eskul);
                $view->with('eskul', $eskul);
            }
        });
    }
}
