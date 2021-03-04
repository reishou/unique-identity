<?php

namespace Reishou\UniqueIdentity;

use Carbon\Carbon;

/**
 * Class UniqueIdentity
 *
 * @package Reishou\UniqueIdentity
 */
class UniqueIdentity
{
    /**
     * @var int
     */
    private $epoch;

    /**
     * UniqueIdentity constructor.
     */
    public function __construct()
    {
        $this->epoch = config('uid.epoch');
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
        $id |= ($shardId << 10);
        $id |= ($seqId);

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
