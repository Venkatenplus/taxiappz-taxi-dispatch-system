<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\APIVersion;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SessionExpired::class,
            \App\Http\Middleware\Localization::class,
            // \App\Http\Middleware\SessionTimeOut::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'security' => [
            \App\Http\Middleware\ProxyMiddleware::class,
            \App\Http\Middleware\BruteForceMiddleware::class,
            \App\Http\Middleware\BadBotMiddleware::class,
            \App\Http\Middleware\FakeBotMiddleware::class,
            \App\Http\Middleware\MassRequestMiddleware::class,
            \App\Http\Middleware\TorMiddleware::class,
            \App\Http\Middleware\SQLInjectionMiddleware::class,
            \App\Http\Middleware\TrustedHTTPHost::class,
            \App\Http\Middleware\XssMiddleware::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' =>
            \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' =>
            \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'api_version' => App\Http\Middleware\APIVersion::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' =>
            \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' =>
            \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
        'settings' => \App\Http\Middleware\SettingsMiddleware::class,
        'jwt.auth' => 'Tymon\JWTAuth\Middleware\GetUserFromToken',
        'jwt.refresh' => 'Tymon\JWTAuth\Middleware\RefreshToken',
        '2fa' => \App\Http\Middleware\Check2FA::class,
        'badbot' => \App\Http\Middleware\BadBotMiddleware::class,
        'blockIP' => \App\Http\Middleware\BlockIpMiddleware::class,
    ];
}
