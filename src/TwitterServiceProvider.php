<?php

/*
 * This file is part of Laravel Twitter.
 *
 * (c) Brian Faust <hello@brianfaust.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\Twitter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;

class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-twitter.php' => config_path('laravel-twitter.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-twitter.php', 'laravel-twitter');

        $this->registerFactory();

        $this->registerManager();

        $this->registerBindings();
    }

    /**
     * Register the factory class.
     */
    protected function registerFactory()
    {
        $this->app->singleton('twitter.factory', function () {
            return new TwitterFactory();
        });

        $this->app->alias('twitter.factory', TwitterFactory::class);
    }

    /**
     * Register the manager class.
     */
    protected function registerManager()
    {
        $this->app->singleton('twitter', function (Container $app) {
            $config = $app['config'];
            $factory = $app['twitter.factory'];

            return new TwitterManager($config, $factory);
        });

        $this->app->alias('twitter', TwitterManager::class);
    }

    /**
     * Register the bindings.
     */
    protected function registerBindings()
    {
        $this->app->bind('twitter.connection', function (Container $app) {
            $manager = $app['twitter'];

            return $manager->connection();
        });

        $this->app->alias('twitter.connection', Twitter::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [
            'twitter',
            'twitter.factory',
            'twitter.connection',
        ];
    }
}
