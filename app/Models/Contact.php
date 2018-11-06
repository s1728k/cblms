<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	public $table = "contact";
    protected $fillable = ['name', 'email', 'phone', 'message'];
}
