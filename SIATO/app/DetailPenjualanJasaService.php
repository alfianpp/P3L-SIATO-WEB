<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualanJasaService extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_penjualan_jasaservice';

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
        'id_detail_penjualan', 'id_jasaservice',
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

    public function jasa_service()
    {
        return $this->belongsTo('App\JasaService', 'id_jasaservice');
    }
}
