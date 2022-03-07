<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use RecordSignature;

    protected $connection = 'MESDB';
    protected $table = 'companies';
}
