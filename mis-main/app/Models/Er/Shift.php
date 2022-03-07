<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use App\Models\Er\Company;

class Shift extends Model
{
    use RecordSignature;

    protected $connection = 'MESDB';
    protected $table = 'mst_shift';

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
