<?php

namespace Reishou\UniqueIdentity;

use Illuminate\Support\ServiceProvider;
use Reishou\UniqueIdentity\Console\TableCommand;

class UidServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/uid.php', 'uid');

        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    TableCommand::class,
                ]
            );
        }
    }

    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/uid.php' => config_path('uid.php'),
            ]
        );
    }
}
