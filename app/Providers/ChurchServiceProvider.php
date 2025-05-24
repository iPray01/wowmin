<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use NumberFormatter;

class ChurchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register currency formatter
        $this->app->singleton('currency.formatter', function ($app) {
            return new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        });
    }

    public function boot(): void
    {
        // Register Blade directives
        Blade::directive('money', function ($amount) {
            return "<?php echo config('church.currency.symbol') . number_format($amount, config('church.currency.decimal_places')); ?>";
        });

        Blade::directive('date', function ($expression) {
            return "<?php echo date(config('church.date_format.display'), strtotime($expression)); ?>";
        });

        Blade::directive('datetime', function ($expression) {
            return "<?php echo date(config('church.date_format.datetime'), strtotime($expression)); ?>";
        });

        // Register view composers
        view()->composer('*', function ($view) {
            $view->with('churchColors', config('church.branding.colors'));
        });
    }
} 