<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($cabang) {
            foreach($cabang->penjualan as $penjualan) {
                $penjualan->delete();
            }
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cabang';

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

    public function penjualan()
    {
        return $this->hasMany('App\Penjualan', 'id_cabang');
    }

    // belongsTo
}
