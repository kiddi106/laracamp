<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordSignature;

    protected $connection = 'MESDB';
    protected $table = 'departments';
    protected $keyType = 'string';
    public $incrementing = false;
    
    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
