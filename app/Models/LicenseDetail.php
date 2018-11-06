<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseDetail extends Model
{
	public $table = "license_detail";
    protected $fillable = ['license_id', 'hardware_code', 'computer_name', 'computer_user'];

    public function license()
    {
        return $this->belongsTo('App\Models\license');
    }
}
