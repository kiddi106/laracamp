<?php

namespace App\Models\Auth;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use RecordSignature;

    protected $table = 'departments';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
}
