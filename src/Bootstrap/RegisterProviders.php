<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Bootstrap;

use LaravelZero\Framework\Providers;
use LaravelZero\Framework\Commands\Component;
use Illuminate\Contracts\Foundation\Application;
use NunoMaduro\LaravelDesktopNotifier\LaravelDesktopNotifierServiceProvider;
use Illuminate\Foundation\Bootstrap\RegisterProviders as BaseRegisterProviders;

/**
 * This is the Laravel Zero Framework Bootstrap Register Providers implementation.
 */
class RegisterProviders extends BaseRegisterProviders
{
    /**
     * Framework core providers.
     *
     * @var string[]
     */
    protected $providers = [
        Providers\Cache\CacheServiceProvider::class,
        Providers\Filesystem\FilesystemServiceProvider::class,
        Providers\Composer\ComposerServiceProvider::class,
        LaravelDesktopNotifierServiceProvider::class,
    ];

    /**
     * Framework optional components.
     *
     * @var string[]
     */
    protected $components = [
        Component\Illuminate\Log\ComponentProvider::class,
        Component\Illuminate\Database\ComponentProvider::class,
    ];

    /**
     * Bootstrap register providers.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function bootstrap(Application $app): void
    {
        /*
         * First, we register Laravel Foundation providers.
         */
        parent::bootstrap($app);

        /*
         * Then we register Laravel Zero providers.
         */
        collect($this->providers)->merge(
            collect($this->components)->filter(
                function ($component) use ($app) {
                    return (new $component($app))->isAvailable();
                }
            )
        )->each(
            function ($serviceProviderClass) use ($app) {
                $app->register($serviceProviderClass);
            }
        );
    }
}
