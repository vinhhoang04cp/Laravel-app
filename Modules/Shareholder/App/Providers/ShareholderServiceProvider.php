<?php

namespace Modules\Shareholder\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Shareholder\App\Observers\ShareholderObserver;
use Modules\Shareholder\App\Models\Shareholder;

class ShareholderServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Shareholder';

    protected string $moduleNameLower = 'shareholder';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/migrations'));
        Shareholder::observe(ShareholderObserver::class);


        // ---- Map các quyền sang Gate
        $abilities = [
            // Congress
            'browse_congress_root', 'browse_congresses', 'add_congresses', 'edit_congresses',
            'delete_congresses', 'view_congresses', 'import_congresses',
            'browse_congress_shareholders', 'delete_congress_shareholders',

            // Shareholder
            'browse_shareholder_root', 'browse_shareholders', 'add_shareholders',
            'edit_shareholders', 'delete_shareholders', 'view_shareholders',
            'invite_shareholders', 'register_shareholders',

            // Mail (ví dụ bạn đang có sẵn)
            'browse_mail_templates', 'read_mail_templates', 'add_mail_templates',
            'edit_mail_templates', 'delete_mail_templates',
            'browse_mail_configs', 'read_mail_configs', 'add_mail_configs',
            'edit_mail_configs', 'delete_mail_configs',
            'send_test_mail',
            'browse_mail_logs', 'read_mail_logs',
            'browse_mail_root',
        ];

        foreach ($abilities as $ability) {
            Gate::define($ability, function ($user) use ($ability) {
                if (!$user) {
                    return false;
                }
                if (method_exists($user, 'hasRole') && $user->hasRole('guest')) {
                    return false;
                }
                return method_exists($user, 'hasPermission')
                    ? $user->hasPermission($ability)
                    : false;
            });
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName.'\\'.config('modules.paths.generator.component-class.path'));
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
