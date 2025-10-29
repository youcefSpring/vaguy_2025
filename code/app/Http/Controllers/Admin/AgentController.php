<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agents=Admin::where('type','agent')->get();
        $pageTitle="Agents";
        // return $agents;
        return view('admin.agent.index',compact('agents','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle="Create Agent";
        return view('admin.agent.create',compact('pageTitle'));  //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $a =new Admin();
       $a->name=$request->name;
       $a->username=$request->username;
       $a->email=$request->email;
       $a->password=bcrypt($request->password);
       $a->type="Agent";
       $a->save();

       return redirect()->route('admin.agent.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agent=Admin::where('type','agent')->findOrFail($id);
        $pageTitle="Edit Agent : ".$agent->name;
        // return $agents;
        return view('admin.agent.edit',compact('agent','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agent=Admin::where('type','agent')->findOrFail($id);
        $pageTitle="Edit Agent : ".$agent->name;
        // return $agents;
        return view('admin.agent.edit',compact('agent','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $a =Admin::findOrFail($id);
        $a->name=$request->name;
        $a->username=$request->username;
        $a->email=$request->email;
        $a->password=bcrypt($request->password);
        $a->type="Agent";
        $a->save();

        return redirect()->route('admin.agent.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
