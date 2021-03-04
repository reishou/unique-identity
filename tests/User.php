<?php

namespace Reishou\UniqueIdentity\Tests;

use Illuminate\Database\Eloquent\Model;
use Reishou\UniqueIdentity\HasUniqueIdentity;

class User extends Model
{
    use HasUniqueIdentity;

    protected $fillable = [

    ];
}
