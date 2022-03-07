<?php

namespace App\Models\Mst;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class MstHoliday extends Model
{
    use RecordSignature;
    protected $table = 'mst_holiday';

}
