<?php

namespace Reishou\UniqueIdentity\Tests\Unit;

use Reishou\UniqueIdentity\Tests\TestCase;
use Reishou\UniqueIdentity\Uid;

class UidTest extends TestCase
{
    public function testGeneratorCanGenerateAndDecomposeId()
    {
        $generator  = new Uid();
        $nextValue  = 123;
        $shardingId = 10;

        $id = $generator->id($nextValue, $shardingId);
        $this->assertIsInt($id);

        $origin = $generator->decompose($id);
        $this->assertIsArray($origin);

        $this->assertEquals($nextValue, $origin['sequence_id']);
        $this->assertEquals($shardingId, $origin['shard_id']);
    }
}
