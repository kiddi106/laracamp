<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use RecordSignature;

    protected $connection = 'HRFDB';
    protected $table = 'mst_candidate';

    protected $primaryKey = 'cand_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
