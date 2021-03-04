<?php

namespace Reishou\UniqueIdentity;

use Carbon\Carbon;

class UniqueIdentity
{
    private $epoch;

    public function __construct()
    {
        $this->epoch = config('unique-identity.epoch');
    }

    /**
     * @param  int  $nextSequenceId
     * @param  int  $shardId
     * @return int
     */
    public function id(int $nextSequenceId, int $shardId = 1): int
    {
        $now   = Carbon::now()->valueOf();
        $time  = $now - $this->epoch;
        $seqId = $nextSequenceId % 1024;

        $id = $time << 23;
        $id = $id | ($shardId << 10);
        $id = $id | ($seqId);

        return $id;
    }

    /**
     * @param  int  $id
     * @return array
     */
    public function decompose(int $id): array
    {
        $time    = ($id >> 23) & 0x1FFFFFFFFFF;
        $shardId = ($id >> 10) & 0x1FFF;
        $seqId   = ($id >> 0) & 0x3FF;

        return [
            'time'        => $time,
            'shard_id'    => $shardId,
            'sequence_id' => $seqId,
        ];
    }
}
