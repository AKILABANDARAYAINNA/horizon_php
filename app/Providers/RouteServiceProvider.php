<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/redirect-by-role';

    public function boot()
    {
        parent::boot();

        Route::get('/redirect-by-role', function () {
            $user = Auth::user();
            return match ($user->role) {
                'admin' => redirect('/admin/dashboard'),
                'agent' => redirect('/agent/dashboard'),
                'client' => redirect('/client/dashboard'),
                default => redirect('/dashboard'),
            };
        })->middleware(['auth', 'verified']);
    }
}
