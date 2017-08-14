<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\User;
use App\Resume;
use Illuminate\Http\Request;

class ResumeController extends AdminController
{
    public function index()
    {
        return view('home');
    }

    public function basic(){
        $this->data['basics'] =  User::with('resume')->where('hasResume',1)->first();
        return view('admin.resume.basics')->with('data',$this->data);
    }

    public function basicConfigUpdate(Request $request){
        $postedData = $request->all();
        //Get the posted value key and value.
        foreach($postedData as $key=>$value){
            if(!in_array($key,['_type','_token'])){
                $columnName = $key;
                $columnValue = $value;
            }
        }

        //If we have not gotten any key/value, there must be some bug, just return back the user request.
        if(!isset($columnValue) || !isset($columnName)){
            return $this->jsonMessage('FAIL','Invalid Request');
        }

        //Now we need to check what is the Type of data that's been sent to us.
        if(isset($postedData->_type) and $postedData->_type === 'checkbox'){
/*            $activeResume = Resume::where('active','1')->first();
            if(!empty($activeResume)){
                //Means we have the record, just add update the Record here.
                $activeResume->$columnName = $columnValue;
            }*/
        }
        elseif(isset($postedData['_type']) and $postedData['_type'] === 'text')
        {
            $activeResume = Resume::where('active','1')->first();
            if(!empty($activeResume)){
                //Means we have the record, just add update the Record here.
                $activeResume->$columnName = $columnValue;
                $boolResult = $activeResume->save();
                if($boolResult){
                    return $this->jsonMessage('OK','Record Successfully Updated for "'.$columnName.'" Having the Updated Value "'.$columnValue);
                }else{
                    return $this->jsonMessage('FAIL','Could Not Update the Record for "'.$columnName.'" Having the Updated Value "'.$columnValue);
                }
            }
        }

        return $this->jsonMessage('FAIL','Something went Wrong, Could Not Update the Record, Please Contact System Administrator for Further Assistance.');
    }

    public function basicsUpdate(Request $request){
        if(!$request){
            return false;
        }
        $userID = $request->get('userID');
        $user = User::find($userID);

        //Need To Validate the Records.
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required'
        ]);

        if(empty($user)){
            return false;
        }


        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->phone = $request['phone'];
        $user->address = $request['address'];

        $resume = Resume::find($user->id);
        $resume->position = $request['position'];
        $resume->available = $request['freelance'];

        $user->save();
        $resume->save();

        return $request['name'];
    }
}
