<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
	public $table = "price";
    protected $fillable = ['subscription_type', 'duration', 'price'];

    public function license()
    {
        return $this->hasMeny('App\Models\license');
    }
}