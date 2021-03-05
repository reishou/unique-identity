<?php

namespace Reishou\UniqueIdentity;

use Illuminate\Support\ServiceProvider;

class UniqueIdentityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/uid.php', 'uid');
        $this->commands([
            'command.uid.table',
        ]);
    }

    public function boot()
    {
        //
    }
}
