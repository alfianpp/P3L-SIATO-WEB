<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoriBarang extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'histori_barang';

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
        'kode_spareparts', 'keluar', 'masuk',
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

    public function spareparts()
    {
        return $this->belongsTo('App\Spareparts', 'kode_spareparts');
    }
}
