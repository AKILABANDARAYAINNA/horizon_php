<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Laravel\Sanctum\PersonalAccessToken as SanctumToken;

class MongoPersonalAccessToken extends SanctumToken
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';
}
