<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    // use RecordSignature;

    protected $connection = 'MESDB';
    protected $table = 'permissions';
}
