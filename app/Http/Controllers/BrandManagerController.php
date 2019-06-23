<?php

namespace App\Http\Controllers;

use App\BrandManagerModel;
use Illuminate\Http\Request;

class BrandManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $brand_list = BrandManagerModel::all();
        return view('brandmanager',[
            'title'=>'Brand Manager',
            'subtitle'=>'',
            'message_type' => '0',//info
            'brand_list'=>$brand_list
        ]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $brandManagerModel = new BrandManagerModel();
        $brandManagerModel->brand_name = $request->get('brand_name');
        $brandManagerModel->save();        
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BrandManagerModel  $brandManagerModel
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BrandManagerModel  $brandManagerModel
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        //
        // print_r($brandManagerModel->id);
        $brandManagerModel = BrandManagerModel::find($id);
        // dd($brandManagerModel->id);

        return view('brand_form',[
            'title' => 'Edit Brand Name',
            'subtitle' =>'',
            'message_type'=>0,
            'brand' => $brandManagerModel
        ]);   

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BrandManagerModel  $brandManagerModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        if($request->get('branddel')){
            $this->destroy($request);
            return redirect()->route('brand_manager_index');        
        }
        $brandManagerModel = BrandManagerModel::find($request->id);
        $brandManagerModel->brand_name = $request->get('brand_name');
        $brandManagerModel->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BrandManagerModel  $brandManagerModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $brandManagerModel = BrandManagerModel::find($request->id);
        $brandManagerModel->delete();
        return redirect()->route('brand_manager_index');        
    }
}
