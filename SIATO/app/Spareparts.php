<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spareparts extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($spareparts) {
            foreach($spareparts->detail_pengadaan_barang as $detail_pengadaan_barang) {
                $detail_pengadaan_barang->delete();
            }

            foreach($spareparts->histori_barang as $histori_barang) {
                $histori_barang->delete();
            }

            foreach($spareparts->detail_penjualan_spareparts as $detail_penjualan_spareparts) {
                $detail_penjualan_spareparts->delete();
            }
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'spareparts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'kode';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
        'kode', 'nama', 'merk', 'tipe', 'kode_peletakan', 'harga_beli', 'harga_jual', 'stok', 'stok_minimal',
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

    public function detail_pengadaan_barang()
    {
        return $this->hasMany('App\DetailPengadaanBarang', 'kode_spareparts');
    }

    public function histori_barang()
    {
        return $this->hasMany('App\HistoriBarang', 'kode_spareparts');
    }

    public function detail_penjualan_spareparts()
    {
        return $this->hasMany('App\DetailPenjualanSpareparts', 'kode_spareparts');
    }

    // belongsTo
}
