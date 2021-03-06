<?php

namespace Reishou\UniqueIdentity\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Reishou\UniqueIdentity\HasUid;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @method static Model|User create($attributes)
 * @package Reishou\Uid\Tests\Models
 */
class User extends Model
{
    use HasUid;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];
}
