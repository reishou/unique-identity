<?php

namespace Reishou\UniqueIdentity;

use Carbon\Carbon;

class UniqueIdentity
{
    public const OUR_EPOCH = 1606089600000; // 2020/11/23 00:00:00

    /**
     * @param  int  $nextSequenceId
     * @param  int  $shardId
     * @return int
     */
    public static function id(int $nextSequenceId, int $shardId = 1)
    {
        $now   = Carbon::now()->valueOf();
        $time  = $now - self::OUR_EPOCH;
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
    public static function decompose(int $id)
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
