<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['po_dtl_id', 'status_id', 'condition', 'esn', 'ssid', 'password_router', 'guest_ssid', 'password_guest', 'password_admin', 'imei', 'device_model', 'device_type', 'color', 'location', 'notes', 'created_by', 'updated_by', 'deleted_by'];

    public function created_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
