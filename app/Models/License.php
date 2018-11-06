<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
	public $table = "license";
    protected $fillable = ['license_key', 'total_licenses', 'activated_licenses', 'created_by', 'expiry_date', 'price_id'];

    public function price()
    {
        return $this->belongsTo('App\Models\price');
    }

    public function licensedetail()
    {
        return $this->hasMeny('App\Models\licensedetail');
    }

    protected $hidden = [
        'created_by'
    ];
}
