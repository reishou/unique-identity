<?php

namespace Reishou\UniqueIdentity\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Reishou\UniqueIdentity\HasUniqueIdentity;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @method static Model|User create($attributes)
 * @package Reishou\UniqueIdentity\Tests\Models
 */
class User extends Model
{
    use HasUniqueIdentity;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];
}
