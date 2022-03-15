<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoDtlUploadHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'po_dtl_upload_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['po_dtl_id', 'filename', 'created_by', 'updated_by', 'deleted_by'];
}
