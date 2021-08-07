<?php

namespace App\Http\Middleware;

use AElnemr\RestFullResponse\CoreJsonResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Laravel\Passport\Exceptions\OAuthServerException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
