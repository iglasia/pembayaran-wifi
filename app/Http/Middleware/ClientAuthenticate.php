<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ClientAuthenticate extends Middleware
{
    /**
     * Metode untuk memeriksa apakah seorang klien sudah login atau belum.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        // Paksa untuk selalu memeriksa guard 'client'.
        if ($this->auth->guard('client')->check()) {
            return $this->auth->shouldUse('client');
        }

        // Jika tidak login, panggil metode unauthenticated.
        $this->unauthenticated($request, ['client']);
    }

    /**
     * Metode untuk mengarahkan pengguna jika mereka BELUM login.
     * Ini adalah bagian penting yang memperbaiki masalah Anda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Jika request bukan untuk API...
        if (! $request->expectsJson()) {
            // ...arahkan mereka ke halaman login khusus untuk klien.
            return route('client.login');
        }
    }
}