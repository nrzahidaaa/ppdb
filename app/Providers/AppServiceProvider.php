<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\TahunAjaran;
use App\Helpers\TahunAjaranHelper;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailwind');

        View::composer('*', function ($view) {
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();
        $selectedTahunAjaran = TahunAjaranHelper::getSelected();

        $view->with('globalTahunAjarans', $tahunAjarans);
        $view->with('selectedTahunAjaran', $selectedTahunAjaran);
    });
    }
}