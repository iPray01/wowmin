<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\HandleCors as Middleware;

class HandleCors extends Middleware
{
    /**
     * The allowed origins.
     *
     * @var array|string|null
     */
    protected $except = [
        //
    ];
} 