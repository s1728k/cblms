<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class ApiController extends Controller
{

    protected $request;
    protected $table;
    protected $created_by;
    protected $super_user;
    protected $timezone;

    public function __construct(Request $request)
    {
        // $this->middleware('auth');
        $this->request = $request;
        if(!empty(\Auth::user())){
            $this->created_by = \Auth::user()->id;
            if ($this->created_by == 1){
                $this->super_user = $this->created_by;
            }
        }
    }

    public function postCommands($cmd)
    {
        switch ($cmd) {
            case 'new':
                \LOG::Info('new');
                $this->table::create($this->request->all());
                return response()->json(['status' => 'success']);
                break;

            case 'all':
                \LOG::Info('all');
                if($this->created_by == $this->super_user){
                    $table = $this->table::all();
                }else{
                    $table = $this->table::where('created_by', $this->created_by)->get();
                }
                return response()->json($table);
                break;

            default:
                # code...
                break;
        }
    }

    public function postCmdWithParam1($cmd, $param)
    {
        switch ($cmd) {
            case 'get':
                \LOG::Info('put');
                $record = $this->table::find($param);                
                if(!empty($record)){
                    if($this->created_by == $this->super_user){
                        return response()->json($record);
                    }else{
                        if($record->created_by == $this->created_by){
                            return response()->json($record);
                        }
                    }
                }
                else{
                    return response()->json(['status' => 'fail']);
                }
                break;

            case 'put':
                \LOG::Info('put');
                \LOG::Info($this->request);
                $record = $this->table::find($param);
                if(!empty($record)){
                    if($this->created_by == $this->super_user){
                        $record->fill($this->request->all())->save();
                        return response()->json(['status' => 'success']);
                    }else{
                        if($record->created_by == $this->created_by){
                            $record->fill($this->request->all())->save();
                            return response()->json(['status' => 'success']);
                        }
                    }
                }
                else{
                    return response()->json(['status' => 'fail']);
                }
                break;

            case 'delete':
                \LOG::Info('delete');
                $record = $this->table::find($param);
                if(!empty($record)){
                    if($this->created_by == $this->super_user){
                        if($this->table::destroy($param)){
                             return response()->json(['status' => 'success']);
                        }
                    }else{
                        if($record->created_by == $this->created_by){
                            if($this->table::destroy($param)){
                                 return response()->json(['status' => 'success']);
                            }
                        }
                    }
                }
                else{
                    return response()->json(['status' => 'fail']);
                }
                break;

            case 'has':
                \LOG::Info('has');
                $hasarr = explode("+", $param);
                $record = $this->table::find($param);                
                if(!empty($record)){
                    if($this->created_by == $this->super_user){
                        return response()->json($record);
                    }else{
                        if($record->created_by == $this->created_by){
                            return response()->json($record);
                        }
                    }
                }
                else{
                    return response()->json(['status' => 'fail']);
                }
                break;

            default:
                # code...
                break;
        }
    }
}