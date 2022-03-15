<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Simcard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simcards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['po_dtl_id', 'status_id', 'condition', 'serial_no', 'msisdn', 'exp_at', 'location', 'notes', 'updated_by', 'deleted_by'];

    public function created_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
