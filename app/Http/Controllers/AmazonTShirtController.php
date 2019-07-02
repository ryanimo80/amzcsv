<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KeywordModel;
use App\ProfileModel;
use App\CSVDataModel;

class AmazonTShirtController extends Controller
{
    
    public function index()
    {
        // return redirect("/amz/export");
    	$kw = KeywordModel::all()->pluck('main_keyword', 'id');
    	// $kw->splice(0, 0, ['Default']);
    	$prf = ProfileModel::all()->pluck('name','id');
    	return view('amz_tshirt_form', 
    		[
    			'title'=>'Amazon T-Shirt CSV Generator',
    			'subtitle'=>'',
    			'message_type' => '0',//info
    			'kws_group_name'=>$kw,
    			'profile_list'=>$prf,
    		]);
    }

    public function genMockup(Request $req)
    {
    	# code...
    	$profile = new ProfileModel();
    	$selected_profile = $profile->where('id', $req->profile_id)->firstOrFail();
    	$colors = json_decode($selected_profile->color);
    	//{"unisex-t-shirt":["black","red","royal"],"tank-top":["red"],"hoodie":["red","royal"],"long-sleeve":["red","sapphia"],"v-neck":["red","sky"],"men-t-shirt":["red","berry"],"women-t-shirt":["black","blue","red"]}
    	foreach ($colors as $type => $color) {
    		# code...
    		foreach ($color as $value) {
    			# code...
		    	gen_mockup($type, 'Front', $req->file, $value);
    		}
    	}
    }
    
    // public function show(Request $req)
    // {
    // 	# code...
    // 	$csv = CSVDataModel::find($req->id);
    // 	return view('edit_csv',[
    // 		'csv' => $csv
    // 	]);
    // }

    // public function store(Request $request)
    // {
    // 	$is_save_keywords = isset($request->save_kw)?true:false;
	    
	   //  if($is_save_keywords){
	   //  	// luu keywords
		  //   $validatedData = $request->validate([
		  //       // 'main_keyword' => 'required|max:100',
		  //       'title' => 'required|max:100',
		  //       'bulletpoint1' => 'required|max:500',
		  //       'bulletpoint2' => 'required|max:500',
		  //       'bulletpoint3' => 'required|max:500',
		  //       'bulletpoint4' => 'required|max:500',
		  //       'bulletpoint5' => 'required|max:500',
		  //       'search_term1' => 'required|max:250',
		  //       'search_term2' => 'required|max:250',
		  //       'search_term3' => 'required|max:250',
		  //       'search_term4' => 'required|max:250',
		  //       'search_term5' => 'required|max:250',
		  //       'description' => 'required',		        
		  //   ]);

		  //   $kwmodel = new KeywordModel();
		  //   $kwmodel->main_keyword = $request->main_keyword;
		  //   $kwmodel->bulletpoint_1 = $request->bulletpoint1;
		  //   $kwmodel->bulletpoint_2 = $request->bulletpoint2;
		  //   $kwmodel->bulletpoint_3 = $request->bulletpoint3;
		  //   $kwmodel->bulletpoint_4 = $request->bulletpoint4;
		  //   $kwmodel->bulletpoint_5 = $request->bulletpoint5;
		  //   $kwmodel->searchtearm_1 = $request->search_term1;
		  //   $kwmodel->searchtearm_2 = $request->search_term2;
		  //   $kwmodel->searchtearm_3 = $request->search_term3;
		  //   $kwmodel->searchtearm_4 = $request->search_term4;
		  //   $kwmodel->searchtearm_5 = $request->search_term5;
		  //   $kwmodel->description = $request->description;		   
		  //   $kwmodel->save();

	   //  }else{
		  //   $validatedData = $request->validate([
		  //       // 'main_keyword' => 'required|max:100',
		  //       'title' => 'required|max:100',
		  //       'skuid' => 'required|integer',
		  //       'bulletpoint1' => 'required|max:500',
		  //       'bulletpoint2' => 'required|max:500',
		  //       'bulletpoint3' => 'required|max:500',
		  //       'bulletpoint4' => 'required|max:500',
		  //       'bulletpoint5' => 'required|max:500',
		  //       'search_term1' => 'required|max:250',
		  //       'search_term2' => 'required|max:250',
		  //       'search_term3' => 'required|max:250',
		  //       'search_term4' => 'required|max:250',
		  //       'search_term5' => 'required|max:250',
		  //       'description' => 'required',
		  //       'pngs' => 'required'
		  //   ]);	
	   //  }


    // 	return view('amz_tshirt_form', 
    // 		[
    // 			'title'=>'Amazon T-Shirt Form',
    // 			'subtitle'=>$is_save_keywords?"Keyword saved!":'',
    // 		]);
    // }

    /**
     * Upload design
     */
    public function uploads(Request $request)
    {

    	$kw = KeywordModel::all()->pluck('main_keyword', 'id');
    	$prf = ProfileModel::all()->pluck('name', 'id');

	    $validatedData = $request->validate([
	        'pngs' => 'required',
	        'profile_id' => 'required'
	    ]);

	    $files = [];
	    $month_index = date('m')-1;
	    // $selected_profile = $request->profile_name;
	    $profile = ProfileModel::where('id', $request->profile_id)->first();
	    $keywords = KeywordModel::where('id', $request->kw_group_name)->first();

	    if($keywords==null){
	    	$keywords = new \stdClass;
	    	$keywords->bulletpoint_1='';
		    $keywords->bulletpoint_2='';
	    	$keywords->bulletpoint_3='';
	    	$keywords->bulletpoint_4='';
	    	$keywords->bulletpoint_5='';
	    	$keywords->searchterm_1='';
	    	$keywords->searchterm_2='';
	    	$keywords->searchterm_3='';
	    	$keywords->searchterm_4='';
	    	$keywords->searchterm_5='';
	    	$keywords->description='';
	    }
	    $keywords->description = str_replace(array("\n", "\r"), '\n', $keywords->description);
	    $keywords->description = str_replace('\n\n', '\n', $keywords->description);

        if($request->hasfile('pngs'))
         {
            
            foreach($request->file('pngs') as $file)
            {
                $name=$file->getClientOriginalName();
                // $path = storage_png_path().'/files/'.time();
                $path = storage_png_path().'/files/'.Str::random(2);

                $file->move($path, $name);
                $files[] = $path.'/'.$name;
            }
         }

    	return view('amz_tshirt_form', 
    		[
    			'title'=>'Amazon T-Shirt Form',
    			'message_type' => 0,
    			'subtitle'=>"",
    			'kws_group_name'=>$kw,
    			'files'=>$files,
    			'month_index' => $month_index,
    			'profile_list' => $prf,
    			'profile' => $profile,
    			'keywords' => $keywords
    		]);
    }


    /**
     * Fix error
     */
    public function fix_db_error()
    {
    	# code...
    	$csvdata = CSVDataModel::where('id','>', 1307)->get();
    	// print_r($csvdata);
    	foreach ($csvdata as $key => $value) {
    		// Update item_sku
    		echo $value->item_sku.' => ';
            echo $value->filepng;
    		// $value->item_sku = str_replace('06','5',$value->item_sku);
    		# $value->item_sku = preg_replace('/^19/', randomstring(1).'19', $value->item_sku, 1);
    		// $value->item_sku = str_replace('-',randomstring(1),$value->item_sku);
    		echo '<br/>';
    		// echo $value->profile_id;
    		// $value->profile_id = 3;
    		// $value->save();
    		// echo '<br/>';
            copy($value->filepng, "/mnt/artwork/2019/T14/".basename($value->filepng));
    	}
    }

    public function test()
    {
        # code...
    }
}
