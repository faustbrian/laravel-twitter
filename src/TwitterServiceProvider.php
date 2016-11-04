<?php

namespace BrianFaust\Twitter;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $source = realpath(__DIR__.'/../config/twitter.php');

        $this->publishes([$source => config_path('twitter.php')]);

        $this->mergeConfigFrom($source, 'twitter');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
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
    public function provides()
    {
        return [
            'twitter',
            'twitter.factory',
            'twitter.connection',
        ];
    }
}
