<?php

namespace Reishou\UniqueIdentity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Trait HasUniqueIdentity
 *
 * @package Reishou\UniqueIdentity
 */
trait HasUniqueIdentity
{
    /**
     * Booting HasUniqueIdentity
     */
    public static function bootHasUniqueIdentity()
    {
        static::creating(
            function (Model $model) {
                $model->setIncrementing(false);
                $model->{$model->getKeyName()} = $model->uid()[0];
            }
        );
    }

    /**
     * @param int $count
     * @return array
     */
    protected function uid(int $count = 1): array
    {
        $next      = $this->getNextSequence($count);
        $generator = $this->getUidGenerator();

        return array_map(
            function ($cnt) use ($generator) {
                return $generator->id($cnt);
            },
            range($next - $count + 1, $next)
        );
    }

    /**
     * @return UniqueIdentity
     */
    protected function getUidGenerator(): UniqueIdentity
    {
        return new UniqueIdentity();
    }

    /**
     * @param int $count
     * @return mixed
     */
    private function getNextSequence(int $count = 1)
    {
        $table = $this->getTable();

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
        DB::table(config('uid.entity_table'))
            ->useWritePdo()
            ->where('entity', $sequence->entity)
            ->update(['next_value' => $sequence->next_value + $count]);
    }

    /**
     * @param string $table
     * @param int    $count
     */
    private function createEntitySequence(string $table, int $count)
    {
        DB::table(config('uid.entity_table'))
            ->useWritePdo()
            ->insert(
                [
                    'entity' => $table,
                    'next_value' => $count + 1,
                ]
            );
    }

    /**
     * @param string $table
     * @return Model|Builder|object|null
     */
    private function getFirstEntitySequence(string $table)
    {
        return DB::table(config('uid.entity_table'))
            ->useWritePdo()
            ->where('entity', $table)
            ->lockForUpdate()
            ->first();
    }
}
