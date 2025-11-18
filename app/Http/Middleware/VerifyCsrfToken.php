<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // ✅ TAMBAHKAN INI - SEMUA API ROUTES
        'api/*',
        'api/books',
        'api/books/*',
        'books',
        'books/*'
    ];
}
