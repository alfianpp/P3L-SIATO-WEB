<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPengadaanBarang extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_pengadaan_barang';

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
        'id_pengadaan_barang', 'kode_spareparts', 'jumlah_pesan', 'jumlah_datang', 'harga'
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

    public function pengadaan_barang()
    {
        return $this->belongsTo('App\PengadaanBarang', 'id_pengadaan_barang');
    }

    public function spareparts()
    {
        return $this->belongsTo('App\Spareparts', 'kode_spareparts');
    }
}
