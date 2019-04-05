<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
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

    public function detail()
    {
        return $this->hasMany('App\DetailPenjualan', 'id_penjualan');
    }
}
