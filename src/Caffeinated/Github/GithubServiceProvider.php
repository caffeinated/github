<?php
namespace Caffeinated\Github;

use Illuminate\Support\ServiceProvider;

class GithubServiceProvider extends ServiceProvider
{
    /**
	 * @var bool $defer Indicates if loading of the provider is deferred.
	 */
	protected $defer = false;

    /**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__.'/../../config/github.php' => config_path('github.php'),
		]);
	}

    /**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__.'/../../config/github.php', 'github'
		);

        $this->registerFactory($this->app);
        $this->registerGithub($this->app);
    }

	/**
	 * Register the GitHub factory class.
	 *
	 * @param  App  $app
	 * @return null
	 */
    protected function registerFactory($app)
    {
        $app->singleton('github.factory', function($app) {
            $auth = new Authenticators\AuthenticatorFactory();
            $path = $app['path.storage'].'/github';

            return new Factory($auth, $path);
        });

        $app->alias('github.factory', 'Caffeinated\Github\Factory');
    }

	/**
	 * Register the GitHub class.
	 *
	 * @param  App  $app
	 * @return null
	 */
    protected function registerGithub($app)
    {
        $app->singleton('github', function($app) {
            $config  = $app['config'];
            $factory = $app['github.factory'];

            return new Github($config, $factory);
        });

        $app->alias('github', 'Caffeinated\Github\Github');
    }

    /**
	 * Get the services provided by the provider.
	 *
	 * @return string
	 */
	public function provides()
	{
		return ['github', 'github.factory'];
	}
}
