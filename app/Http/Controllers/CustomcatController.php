<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomcatSkuSettingModel;

class CustomcatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $clothing_config = clothing_config();
        $product = array();
        $color = array();
        $title = 'Customcat setting';
        $current_setting = CustomcatSkuSettingModel::find($req->id);

        /**
         * Save config
         */
        if($req->isMethod('post') && $req->get('submit')){
            $validator = $req->validate([
                'select_product' => 'bail|required',
                'select_color' => 'bail|required', 
                'size_sku' => 'bail|required', 
            ]);

            if(intval($req->id)>0){
                $current_setting->short_code = $req->select_product;
                $current_setting->color = $req->select_color;
                $current_setting->size_sku = $req->size_sku;
                $current_setting->save();
                $title = 'Update customcat setting successful!';
            }else{
                $insert = CustomcatSkuSettingModel::create(array(
                    'short_code' => $req->select_product,
                    'color' => $req->select_color,
                    'size_sku' => trim($req->size_sku)
                ));                
                $title = 'Customcat setting successful!';
                // return redirect(url()->current()."/".$insert->id);
            }
        }


        foreach ($clothing_config as $key => $value) {
            $product[$value['short_code']] = ucfirst($key);
            foreach ($value['color'] as $k => $v) {
                if(!in_array($v, $color)){
                    $color[$v] = $v;
                }
            }
        }

        $customcat_sku_settings = CustomcatSkuSettingModel::all();
        return view('customcat_setting', [
            'title'=>$title,
            'message_type'=>0,
            'product' => $product,
            'color' => $color,
            'customcat_sku_settings' => $customcat_sku_settings,
            'current_setting' => $current_setting
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
