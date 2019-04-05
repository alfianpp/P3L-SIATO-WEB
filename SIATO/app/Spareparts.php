<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spareparts extends Model
{
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
}
