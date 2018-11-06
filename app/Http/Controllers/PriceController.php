<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;
class PriceController extends ApiController
{

    protected $request;
    protected $table;
    protected $created_by;

    public function __construct(Request $request)
    {
        $this->table = new Price();
        parent::__construct($request);
    }
    
}