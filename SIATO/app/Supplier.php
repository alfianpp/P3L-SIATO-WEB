<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($supplier) {
            foreach($supplier->pengadaan_barang as $pengadaan_barang) {
                $pengadaan_barang->delete();
            }
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'supplier';

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
        'nama', 'alamat', 'nama_sales', 'nomor_telepon_sales',
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

    public function pengadaan_barang()
    {
        return $this->hasMany('App\PengadaanBarang', 'id_supplier');
    }

    // belongsTo
}
