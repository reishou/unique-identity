<?php

namespace Reishou\UniqueIdentity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

trait HasUniqueIdentity
{
    public $incrementing = false;

    /**
     * @param int $count
     * @return mixed
     */
    private function getNextSequence($count = 1)
    {
        $table = $this->getTableName();

        return DB::transaction(
            function () use ($count, $table) {
                $sequence = $this->getFirstEntitySequence($table);

                if (!$sequence) {
                    $this->createEntitySequence($table, $count);

                    return $count;
                }

                $this->updateEntitySequence($sequence, $count);

                return $sequence->next_value + $count - 1;
            }
        );
    }

    /**
     * @param $sequence
     * @param $count
     */
    private function updateEntitySequence($sequence, $count)
    {
        DB::table('entity_sequences')
            ->where('entity', $sequence->entity)
            ->update(['next_value' => $sequence->next_value + $count]);
    }

    /**
     * @param $table
     * @param $count
     */
    private function createEntitySequence($table, $count)
    {
        DB::table('entity_sequences')
            ->insert(
                [
                    'entity'     => $table,
                    'next_value' => $count + 1,
                ]
            );
    }

    /**
     * @param $table
     * @return Model|Builder|object|null
     */
    private function getFirstEntitySequence($table)
    {
        return DB::table('entity_sequences')
            ->select('next_value')
            ->where('entity', $table)
            ->lockForUpdate()
            ->first();
    }

    /**
     * @return mixed
     */
    public static function getTableName()
    {
        return with(new static())->getTable();
    }
}
