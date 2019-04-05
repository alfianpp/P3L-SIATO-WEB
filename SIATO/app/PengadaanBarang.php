<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengadaanBarang extends Model
{
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
        'id_supplier',
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

    public function detail()
    {
        return $this->hasMany('App\DetailPengadaanBarang', 'id_pengadaan_barang');
    }
}
