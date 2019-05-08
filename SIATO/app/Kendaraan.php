<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($kendaraan) {
            foreach($kendaraan->detail_penjualan as $detail_penjualan) {
                $detail_penjualan->delete();
            }
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kendaraan';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'nomor_polisi';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_polisi', 'merk', 'tipe', 'id_pemilik',
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

    public function detail_penjualan()
    {
        return $this->hasMany('App\DetailPenjualan', 'nomor_polisi');
    }

    // belongsTo

    public function pemilik()
    {
        return $this->belongsTo('App\Konsumen', 'id_pemilik');
    }
}
