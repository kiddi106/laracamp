<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Po extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'po';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['po_no', 'po_at', 'delivery_no', 'receive_at', 'description', 'currency', 'kurs', 'created_by', 'updated_by', 'deleted_by'];

    public function dtls()
    {
        return $this->hasMany(PoDtl::class, 'po_id', 'id');
    }

    public function created_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
