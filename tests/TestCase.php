<?php

namespace Reishou\UniqueIdentity\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Reishou\UniqueIdentity\UidServiceProvider;

/**
 * Class UidTest
 *
 * @package Reishou\Uid\Tests
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(
            [
                '--database' => 'testing',
                '--path'     => realpath(__DIR__ . '/../tests/migrations'),
            ]
        );
    }

    /**
     * @param $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [UidServiceProvider::class];
    }

    /**
     * @param $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set(
            'database.connections.testing',
            [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]
        );
    }
}
