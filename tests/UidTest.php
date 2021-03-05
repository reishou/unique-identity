<?php

namespace Reishou\UniqueIdentity\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;
use Reishou\UniqueIdentity\Tests\Models\User;
use Reishou\UniqueIdentity\UniqueIdentity;
use Reishou\UniqueIdentity\UniqueIdentityServiceProvider;

/**
 * Class UidTest
 * @package Reishou\UniqueIdentity\Tests
 */
class UidTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path'     => realpath(__DIR__.'/../tests/migrations'),
        ]);
    }

    /**
     * @param $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [UniqueIdentityServiceProvider::class];
    }

    /**
     * @param $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function testGeneratorCanGenerateAndDecomposeId()
    {
        $generator  = new UniqueIdentity();
        $nextValue  = 123;
        $shardingId = 10;

        $id = $generator->id($nextValue, $shardingId);
        $this->assertIsInt($id);

        $origin = $generator->decompose($id);
        $this->assertIsArray($origin);

        $this->assertEquals($nextValue, $origin['sequence_id']);
        $this->assertEquals($shardingId, $origin['shard_id']);
    }

    public function testModelCanGenerateIds()
    {
        $count = 10;
        $ids   = User::uid($count);

        $this->assertIsArray($ids);
        $this->assertCount($count, $ids);

        $ids = array_unique($ids);
        $this->assertCount($count, $ids);
    }

    public function testUidSortableByTime()
    {
        $id1 = User::uid();
        $id2 = User::uid();
        $this->assertGreaterThan($id1, $id2);
    }

    public function testModelMustGenerateUidWhenCreating()
    {
        $attributes = ['name' => 'Reishou'];
        $user       = User::create($attributes);

        $this->assertIsInt($user->id);
        $this->assertNotEquals(1, $user->id);
    }
}
