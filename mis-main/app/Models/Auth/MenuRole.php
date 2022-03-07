<?php

namespace App\Models\Auth;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class MenuRole extends Model
{
    // use RecordSignature;

    protected $table = 'menu_role';
    public $timestamps = false;
}
