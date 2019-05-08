<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($konsumen) {
            foreach($konsumen->kendaraan as $kendaraan) {
                $kendaraan->delete();
            }

            foreach($konsumen->penjualan as $penjualan) {
                $penjualan->delete();
            }
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'konsumen';

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
        'nama', 'nomor_telepon', 'alamat',
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

    public function kendaraan()
    {
        return $this->hasMany('App\Kendaraan', 'id_pemilik');
    }

    public function penjualan()
    {
        return $this->hasMany('App\Penjualan', 'id_konsumen');
    }

    // belongsTo
}
