<?php

namespace Reishou\UniqueIdentity\Tests\Unit;

use Reishou\UniqueIdentity\Tests\Models\User;
use Reishou\UniqueIdentity\Tests\TestCase;

class HasUidTest extends TestCase
{
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
}
