<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
class ContactController extends ApiController
{

    protected $request;
    protected $table;
    protected $created_by;

    public function __construct(Request $request)
    {
        $this->table = new Contact();
        parent::__construct($request);
    }
    
}