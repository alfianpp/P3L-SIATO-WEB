<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualanSpareparts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_penjualan_spareparts';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_detail_penjualan', 'kode_spareparts', 'jumlah',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Relationship
     */

    // hasMany

    // belongsTo

    public function detail_penjualan()
    {
        return $this->belongsTo('App\DetailPenjualan', 'id_detail_penjualan');
    }

    public function spareparts()
    {
        return $this->belongsTo('App\Spareparts', 'kode_spareparts');
    }
}
