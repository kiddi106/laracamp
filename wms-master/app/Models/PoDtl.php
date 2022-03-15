<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoDtl extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'po_dtl';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['po_id', 'material_id', 'qty', 'uom', 'price', 'total', 'description'];

    public function po()
    {
        return $this->hasOne(Po::class, 'id', 'po_id');
    }

    public function material()
    {
        return $this->hasOne(Material::class, 'id', 'material_id');
    }

    public function routers()
    {
        return $this->hasMany(Router::class, 'po_dtl_id', 'id');
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'qty' => 'integer',
        'price' => 'integer',
    ];
}
