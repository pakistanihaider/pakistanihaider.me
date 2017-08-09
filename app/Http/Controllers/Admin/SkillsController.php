<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Skill;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SkillsController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resources
     */
    public function index()
    {
//        flash('Hello World')->important();
        $this->data['skills'] = Skill::all();
        return view('admin.skills.list')->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    //Store the Data in database
    public function store(Request $request)
    {
        $this->validate($request,[
            'skill' => 'required'
        ]);

        try{
            $skill = Skill::create([
                'name' => $request['skill'],
                'label' => str_replace(' ','_',$request['skill'])
            ]);
        }catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == '1062'){
                $errorMsg = 'Record Already exist for "'.$request['skill'].'"';
                return $this->jsonMessage('FAIL',$errorMsg);
            }
            return $this->jsonMessage('FAIL','Could Not Add the Entry for "'.$request['skill'].'" Having the Code "'.$error_code);
        }

        if($skill){
            return $skill;
        }else{
            return 'FAIL::Could not add record to the database::error';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function show(Skill $skill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Skill $skill)
    {
        return [
            'skill'=>$skill['name'],
            'percentage' => $skill['percentage']
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'skillID' => 'required|integer',
            'skill' => 'required'
        ]);

//        Skill::updateOrInsert();
        $Skill = Skill::find($request['skillID']);
        $Skill->name = $request['skill'];
        $Skill->percentage = $request['percentage'];
        $boolResult = $Skill->save();
        if($boolResult){
            $successMsg = 'Record Successfully Updated';
            return $this->jsonMessage('OK',$successMsg);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Skill $skill)
    {
        $boolResult = $skill->delete();
        if($boolResult){
            echo 'OK::Record Successfully Deleted::success';
        }
    }
}
