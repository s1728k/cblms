<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class UserController extends ApiController
{

    protected $request;
    protected $table;

    public function __construct(Request $request)
    {
        $this->table = new User();
        parent::__construct($request);
    }

    public function postCommands($cmd)
    {
        switch ($cmd) {
            case 'register':
                \LOG::Info('register');
                \LOG::Info($this->request);
                $email = $this->request->input("email");
                $api_token = md5(uniqid($email, true));
                $keys = md5(uniqid(1, true));
                $this->table::create([
                    'name' => $this->request->input("name"),
                    'email' => $this->request->input("email"),
                    'api_token' => $api_token,
                ]);
                $subject = "Cloud Based License Management System - Login Api";
                $message = "Your Login Api:-  $api_token\n";
                $headers = "From: s1728k@gmail.com" . "\r\n";
                if(mail($email, $subject, $message, $headers)){
                    return response()->json(['status' => 'success']);
                }else{
                    return response()->json(['status' => 'failed']);
                }
                break;

            case 'login':
                \LOG::Info('login');
                if(!empty($this->request->user())){
                    return response()->json(['api_token' => $this->request->user()->api_token, 'status' => 'success']);
                }else{
                    return response()->json(['status' => 'failed']);
                }
                break;

            default:
                return parent::postCommands($cmd);
                break;
        }
    }

    public function postCmdWithOptions($cmd, $id){
        //
    }

}