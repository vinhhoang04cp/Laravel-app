<?php

namespace Modules\Mail\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Mail\App\Repositories\MailConfigRepository;
use Modules\Mail\App\Repositories\MailConfigRepositoryInterface;
use Modules\Mail\App\Repositories\MailTemplateRepository;
use Modules\Mail\App\Repositories\MailTemplateRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use TCG\Voyager\Models\Menu;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MailTemplateRepositoryInterface::class, MailTemplateRepository::class);
        $this->app->bind(MailConfigRepositoryInterface::class, MailConfigRepository::class);
    }

    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(base_path('Modules/Mail/routes/api.php'));
        $this->loadRoutesFrom(base_path('Modules/Mail/routes/web.php'));

        // Load migrations & views
        $this->loadMigrationsFrom(base_path('Modules/Mail/App/Database/Migrations'));
        $this->loadViewsFrom(base_path('Modules/Mail/resources/views'), 'mail');

        // ---- Map các quyền sang Gate
        $abilities = [
            'browse_mail_templates', 'read_mail_templates', 'add_mail_templates', 'edit_mail_templates', 'delete_mail_templates',
            'browse_mail_configs', 'read_mail_configs', 'add_mail_configs', 'edit_mail_configs', 'delete_mail_configs',
            'send_test_mail',
            'browse_mail_logs', 'read_mail_logs','browse_mail_root'
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
}
