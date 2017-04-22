<?php



declare(strict_types=1);

namespace BrianFaust\Twitter;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $source = realpath(__DIR__.'/../config/twitter.php');

        $this->publishes([$source => config_path('twitter.php')]);

        $this->mergeConfigFrom($source, 'twitter');
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerFactory();
        $this->registerManager();
        $this->registerBindings();
    }

    /**
     * Register the factory class.
     */
    protected function registerFactory(): void
    {
        $this->app->singleton('twitter.factory', function () {
            return new TwitterFactory();
        });

        $this->app->alias('twitter.factory', TwitterFactory::class);
    }

    /**
     * Register the manager class.
     */
    protected function registerManager(): void
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
    protected function registerBindings(): void
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
