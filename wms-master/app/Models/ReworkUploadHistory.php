<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReworkUploadHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rework_upload_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'created_by', 'updated_by', 'deleted_by'];
}
