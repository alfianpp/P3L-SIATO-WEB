<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JasaService extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jasa_service';

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
        'nama', 'harga_jual',
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
