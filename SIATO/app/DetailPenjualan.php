<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($detail_penjualan) {
            foreach($detail_penjualan->detail_penjualan_spareparts as $detail_penjualan_spareparts) {
                $detail_penjualan_spareparts->delete();
            }

            foreach($detail_penjualan->detail_penjualan_jasaservice as $detail_penjualan_jasaservice) {
                $detail_penjualan_jasaservice->delete();
            }
        });
    }

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
        'id_penjualan', 'nomor_polisi', 'id_montir',
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

    public function detail_penjualan_spareparts()
    {
        return $this->hasMany('App\DetailPenjualanSpareparts', 'id_detail_penjualan');
    }

    public function detail_penjualan_jasaservice()
    {
        return $this->hasMany('App\DetailPenjualanJasaService', 'id_detail_penjualan');
    }

    public function detailSpareparts()
    {
        return $this->hasMany('App\DetailPenjualanSpareparts', 'id_detail_penjualan');
    }

    public function detailJasaService()
    {
        return $this->hasMany('App\DetailPenjualanJasaService', 'id_detail_penjualan');
    }

    public function kendaraan()
    {
        return $this->belongsTo('App\Kendaraan', 'nomor_polisi');
    }

    public function montir()
    {
        return $this->belongsTo('App\Pegawai', 'id_montir');
    }
}
