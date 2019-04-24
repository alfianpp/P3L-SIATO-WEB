<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($penjualan) {
            foreach($penjualan->detail_penjualan as $detail_penjualan) {
                $detail_penjualan->delete();
            }
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penjualan';

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
        'id_cabang', 'jenis', 'id_konsumen', 'diskon', 'uang_diterima', 'id_cs', 'id_kasir',
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

    public function detail_penjualan()
    {
        return $this->hasMany('App\DetailPenjualan', 'id_penjualan');
    }

    public function detail()
    {
        return $this->hasMany('App\DetailPenjualan', 'id_penjualan');
    }

    public function cabang()
    {
        return $this->belongsTo('App\Cabang', 'id_cabang');
    }

    public function konsumen()
    {
        return $this->belongsTo('App\Konsumen', 'id_konsumen');
    }

    public function cs()
    {
        return $this->belongsTo('App\Pegawai', 'id_cs');
    }

    public function kasir()
    {
        return $this->belongsTo('App\Pegawai', 'id_kasir');
    }
}
