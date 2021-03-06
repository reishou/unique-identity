<?php

namespace Reishou\UniqueIdentity\Tests\Feature;

use Reishou\UniqueIdentity\Tests\Models\User;
use Reishou\UniqueIdentity\Tests\TestCase;

/**
 * Class UidTest
 * @package Reishou\Uid\Tests
 */
class UidTest extends TestCase
{
    public function testModelMustGenerateUidWhenCreating()
    {
        $attributes = ['name' => 'Reishou'];
        $user       = User::create($attributes);

        $this->assertIsInt($user->id);
        $this->assertNotEquals(1, $user->id);
    }
}
