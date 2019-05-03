<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengadaanBarang extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($pengadaan_barang) {
            foreach($pengadaan_barang->detail_pengadaan_barang as $detail_pengadaan_barang) {
                $detail_pengadaan_barang->delete();
            }
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengadaan_barang';

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
        'id_supplier', 'status'
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

    public function detail_pengadaan_barang()
    {
        return $this->hasMany('App\DetailPengadaanBarang', 'id_pengadaan_barang');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'id_supplier');
    }

    public function detail()
    {
        return $this->hasMany('App\DetailPengadaanBarang', 'id_pengadaan_barang');
    }
}
