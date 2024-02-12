<?php

namespace Alexlen\Profiler\Providers;

use Alexlen\Dump\Console\Commands\DumpBackup;
use Alexlen\Dump\Console\Commands\DumpBackupClear;
use Alexlen\Dump\Console\Commands\DumpExport;
use Alexlen\Dump\Console\Commands\DumpHelp;
use Alexlen\Dump\Console\Commands\DumpImport;
use Alexlen\Dump\Console\Commands\DumpRestore;
use Alexlen\Profiler\Logger\FileProfilerLogger;
use Alexlen\Profiler\Logger\ProfilerLoggerInterface;
use Alexlen\Profiler\Profiler\Profiler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ProfilerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Profiler::class, function ($app) {
            return new Profiler();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            app(Profiler::class)->addSql($query);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                  \Alexlen\Profiler\Commands\Profiler::class
            ]);
        }

        $this->publishes([__DIR__.'/../config/profiler.php' => config_path('alexlen/profiler.php')], 'db-profiler');

        $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'db-profiler');
    }
}
