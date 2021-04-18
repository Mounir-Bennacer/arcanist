<?php declare(strict_types=1);

namespace Sassnowski\Arcanist;

use function database_path;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Sassnowski\Arcanist\Contracts\WizardRepository;
use Sassnowski\Arcanist\Renderer\BladeResponseRenderer;

class ArcanistServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], ['migrations', 'arcanist-migrations']);

        $this->publishes([
            __DIR__ . '/../database/config/arcanist.php' => config_path('arcanist.php'),
        ], ['config', 'arcanist-config']);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Arcanist::boot($this->app['config']['arcanist']['wizards']);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/arcanist.php', 'arcanist');

        $this->app->bind(
            WizardRepository::class,
            function (Application $app) {
                return $app['config']['arcanist']['wizard_repository'];
            }
        );

        $this->app->when(BladeResponseRenderer::class)
            ->needs('$viewBasePath')
            ->giveConfig('arcanist.renderers.blade.view_base_path');
    }
}
