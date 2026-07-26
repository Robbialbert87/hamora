<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Mou;
use App\Models\Document;

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
        View::composer('layouts.app', function ($view) {
            $expiringMou = Mou::where('status', 'aktif')
                ->whereBetween('akhir_perjanjian', [now(), now()->addDays(30)])
                ->orderBy('akhir_perjanjian', 'asc')
                ->get();

            $expiringDoc = Document::where('status', 'aktif')
                ->whereBetween('tanggal_berlaku', [now(), now()->addDays(30)])
                ->orderBy('tanggal_berlaku', 'asc')
                ->get();

            $view->with([
                'expiringMou' => $expiringMou,
                'expiringDoc' => $expiringDoc,
                'notifikasiCount' => $expiringMou->count() + $expiringDoc->count(),
            ]);
        });
    }
}
