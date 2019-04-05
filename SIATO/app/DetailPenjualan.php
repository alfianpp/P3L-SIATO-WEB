<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_penjualan';

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
        'id_penjualan', 'nomor_polisi_kendaraan', 'id_montir',
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

    public function detailSpareparts()
    {
        return $this->hasMany('App\DetailPenjualanSpareparts', 'id_detail_penjualan');
    }

    public function detailJasaService()
    {
        return $this->hasMany('App\DetailPenjualanJasaService', 'id_detail_penjualan');
    }
}
