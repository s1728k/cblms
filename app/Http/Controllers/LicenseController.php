<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\License;
use App\Models\Price;
use App\Models\LicenseDetail;
class LicenseController extends ApiController
{

    protected $request;
    protected $table;
    protected $created_by;

    public function __construct(Request $request)
    {
        $this->table = new License();
        parent::__construct($request);
    }

    public function postCommands($cmd)
    {
        // $foo = (object) array_merge( (array)$foo, array( 'bar' => '1234' ) );
        switch ($cmd) {
            case 'new':
                \LOG::Info('new_license');
                $license_key = md5(uniqid($this->request->input("email"), true));
                $this->table::create([
                    'license_key' => $license_key,
                    'created_by' => $this->created_by,
                    'total_licenses' => $this->request->input("total_licenses"),
                    'price_id' => $this->request->input("price_id"),
                    'expiry_date' => new \DateTime($this->request->input("expiry_date")),
                ]);
                $license_id = $this->table::where('license_key', $license_key)->first()->id;
                for ($i = 0; $i<$this->request->input("total_licenses"); $i++)
                {
                    LicenseDetail::create([
                        'license_id' => $license_id,
                    ]);
                }
                return response()->json(['status' => 'success', 'license_key' => $license_key]);
                break;

            case 'all':
                \LOG::Info('all');
                if($this->created_by == $this->super_user){
                    $table = $this->table::all(); //::with("price")::all();;
                }else{
                    $table = $this->table::where('created_by', $this->created_by)->get();
                }
                
                $table->map(function ($itable) {
                    $itable['price'] = Price::find($itable->price_id);
                    return $itable;
                });
                return response()->json($table);
                break;

            case 'usage':
                \LOG::Info('usage');
                $license_id = $this->table::where('license_key', $this->request->input("license_key"))->first()->id;
                $table = LicenseDetail::where('license_id', $license_id)->get();
                if($this->created_by == $this->super_user || $this->created_by == $this->table->created_by){
                    return response()->json(['status' => 'success', 'data' => $table]);
                }else{
                    return response()->json(['status' => 'failed']);
                }
                break;

            case 'activate':
                \LOG::Info('activate_license');
                $record = $this->table::where('license_key', $this->request->input("license_key"))->first();
                if(!empty($record)){
                    $urecord2 = LicenseDetail::where('license_id', $record->id)->where('hardware_code', 
                                $this->request->input("hardware_code"))->first();
                    $urecord = LicenseDetail::where('license_id', $record->id)->where('hardware_code', "")->first();
                    if(!empty($urecord2)){
                        return response()->json([
                            'status' => 'success',
                            'expiry_date' => $record->expiry_date,
                            'host' => 'https://cblms.000webhostapp.com/',
                            'available_licenses' => $record->total_licenses - $record->activated_licenses
                        ]);
                    }else if(!empty($urecord)){
                        $record->activated_licenses = $record->activated_licenses + 1;
                        $record->save();

                        $urecord->hardware_code = $this->request->input("hardware_code");
                        $urecord->computer_name = $this->request->input("computer_name");
                        $urecord->computer_user = $this->request->input("computer_user");
                        $urecord->save();

                        return response()->json([
                            'status' => 'success',
                            'expiry_date' => $record->expiry_date,
                            'host' => 'https://cblms.000webhostapp.com/', 
                            'available_licenses' => $record->total_licenses - $record->activated_licenses
                        ]);
                    }else{
                        return response()->json([
                            'status' => 'failed', 
                            'available_licenses' => $record->total_licenses - $record->activated_licenses
                        ]);
                    }

                }
                else{
                    return response()->json([
                        'status' => 'failed', 
                        'available_licenses' => $record->total_licenses - $record->activated_licenses
                    ]);
                }
                break;

            case 'deactivate':
                \LOG::Info('deactivate_license');
                $record = $this->table::where('license_key', $this->request->input("license_key"))->first();
                if(!empty($record)){
                    $urecord2 = LicenseDetail::where('license_id', $record->id)->where('hardware_code', 
                                $this->request->input("hardware_code"))->first();
                    if(!empty($urecord2)){
                        $record->activated_licenses = $record->activated_licenses - 1;
                        $record->save();

                        $urecord2->hardware_code = "";
                        $urecord2->computer_name = "";
                        $urecord2->computer_user = "";
                        $urecord2->save();

                        return response()->json([
                            'status' => 'success', 
                            'available_licenses' => $record->total_licenses - $record->activated_licenses
                        ]);
                    }else{
                        return response()->json([
                            'status' => 'failed', 
                            'available_licenses' => $record->total_licenses - $record->activated_licenses
                        ]);
                    }
                }
                else{
                    return response()->json([
                        'status' => 'failed', 
                        'available_licenses' => 0
                    ]);
                }
                break;

            default:
                return parent::postCommands($cmd);
                break;
        }
    }

    public function postCmdWithOptions($cmd, $id)
    {
        switch ($cmd) {
            case 'put':
                \LOG::Info('put');
                $record = $this->table::find($id);
                \Log::Info($record);
                if(!empty($record)){
                    if($record->created_by == $this->created_by){
                        $record->licensed_to = $this->request->input("licensed_to");
                        $record->price_id = $this->request->input("price_id");
                        $record->expiry_date = new \DateTime($this->request->input("expiry_date"));
                        $record->save();
                        return response()->json(['status' => 'success']);
                    }
                }
                else{
                    return response()->json(['status' => 'fail']);
                }
                break;
            case 'delete':

            default:
                return parent::postCmdWithOptions($cmd, $id);
                break;
        }
    }

}