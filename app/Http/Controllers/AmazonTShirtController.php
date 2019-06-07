<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\KeywordModel;
use App\ProfileModel;
use App\CSVDataModel;
use Excel;
use App\Exports\AmazonCSVExport;
use App\Exports\EbayCSVExport;
use App\Rules\ValidBannedKeyword;

class AmazonTShirtController extends Controller
{
    //
    public function index()
    {
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
    public function show(Request $req)
    {
    	# code...
    	$csv = CSVDataModel::find($req->id);
    	return view('edit_csv',[
    		'csv' => $csv
    	]);
    }
    public function edit(Request $req)
    {
    	# code...
    	$csv = CSVDataModel::find($req->id);

    	if($req->isMethod('POST')){
    		$csv->item_name = $req->item_name;
    		$csv->bulletpoint_1 = $req->bulletpoint_1;
    		$csv->bulletpoint_2 = $req->bulletpoint_2;
    		$csv->bulletpoint_3 = $req->bulletpoint_3;
    		$csv->bulletpoint_4 = $req->bulletpoint_4;
    		$csv->bulletpoint_5 = $req->bulletpoint_5;
    		$csv->searchterm_1 = $req->searchterm_1;
    		$csv->searchterm_2 = $req->searchterm_2;
    		$csv->searchterm_3 = $req->searchterm_3;
    		$csv->searchterm_4 = $req->searchterm_4;
    		$csv->searchterm_5 = $req->searchterm_5;
    		$csv->description = $req->description;
    		$csv->save();
    	}

    	return view('edit_csv',[
    		'title'=>'Edit ',
    		'message_type' =>0,
    		'csv' => $csv
    	]);
    }

    public function store(Request $request)
    {
    	$is_save_keywords = isset($request->save_kw)?true:false;
	    
	    if($is_save_keywords){
	    	// luu keywords
		    $validatedData = $request->validate([
		        // 'main_keyword' => 'required|max:100',
		        'title' => 'required|max:100',
		        'bulletpoint1' => 'required|max:500',
		        'bulletpoint2' => 'required|max:500',
		        'bulletpoint3' => 'required|max:500',
		        'bulletpoint4' => 'required|max:500',
		        'bulletpoint5' => 'required|max:500',
		        'search_term1' => 'required|max:250',
		        'search_term2' => 'required|max:250',
		        'search_term3' => 'required|max:250',
		        'search_term4' => 'required|max:250',
		        'search_term5' => 'required|max:250',
		        'description' => 'required',		        
		    ]);

		    $kwmodel = new KeywordModel();
		    $kwmodel->main_keyword = $request->main_keyword;
		    $kwmodel->bulletpoint_1 = $request->bulletpoint1;
		    $kwmodel->bulletpoint_2 = $request->bulletpoint2;
		    $kwmodel->bulletpoint_3 = $request->bulletpoint3;
		    $kwmodel->bulletpoint_4 = $request->bulletpoint4;
		    $kwmodel->bulletpoint_5 = $request->bulletpoint5;
		    $kwmodel->searchtearm_1 = $request->search_term1;
		    $kwmodel->searchtearm_2 = $request->search_term2;
		    $kwmodel->searchtearm_3 = $request->search_term3;
		    $kwmodel->searchtearm_4 = $request->search_term4;
		    $kwmodel->searchtearm_5 = $request->search_term5;
		    $kwmodel->description = $request->description;		   
		    $kwmodel->save();

	    }else{
		    $validatedData = $request->validate([
		        // 'main_keyword' => 'required|max:100',
		        'title' => 'required|max:100',
		        'skuid' => 'required|integer',
		        'bulletpoint1' => 'required|max:500',
		        'bulletpoint2' => 'required|max:500',
		        'bulletpoint3' => 'required|max:500',
		        'bulletpoint4' => 'required|max:500',
		        'bulletpoint5' => 'required|max:500',
		        'search_term1' => 'required|max:250',
		        'search_term2' => 'required|max:250',
		        'search_term3' => 'required|max:250',
		        'search_term4' => 'required|max:250',
		        'search_term5' => 'required|max:250',
		        'description' => 'required',
		        'pngs' => 'required'
		    ]);	
	    }


    	return view('amz_tshirt_form', 
    		[
    			'title'=>'Amazon T-Shirt Form',
    			'subtitle'=>$is_save_keywords?"Keyword saved!":'',
    		]);
    }

    public function kwjson($id)
    {
    	$kw = array();
    	if(intval($id)>0){
    		$kw = KeywordModel::find(array('id'=>$id));
    	}
    	return response()->json($kw);    	
    }

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
                $path = '/files/'.time();
                $file->move(public_path().$path, $name);
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

    public function saveCSVRow(Request $req)
    {
    	// try{
		    $validatedData = $req->validate([
		        'bulletpoint_1' => 'bail|required|string|max:500',
		        'bulletpoint_2' => 'bail|required|string|max:500',
		        'bulletpoint_3' => 'bail|required|string|max:500',
		        'bulletpoint_4' => 'bail|required|string|max:500',
		        'bulletpoint_5' => 'bail|required|string|max:500',
		        'searchterm_1' => 'bail|required|string|max:250',
		        'searchterm_2' => 'bail|required|string|max:250',
		        'searchterm_3' => 'bail|required|string|max:250',
		        'searchterm_4' => 'bail|required|string|max:250',
		        'searchterm_5' => 'bail|required|string|max:250',
		        'description' => 'bail|required',		        
		    ]);

	    	$profile = new ProfileModel();
	    	$profile = $profile->where('id', $req->selected_profile)->firstOrFail();
	    	$colors = json_decode($profile->color);
	    	//{"unisex-t-shirt":["black","red","royal"],"tank-top":["red"],"hoodie":["red","royal"],"long-sleeve":["red","sapphia"],"v-neck":["red","sky"],"men-t-shirt":["red","berry"],"women-t-shirt":["black","blue","red"]}
	    	$mockup = array();
	    	foreach ($colors as $type => $color) {
	    		# code...
	    		$mockup[$type] = array();
	    		foreach ($color as $value) {
	    			# code...
	    			$side = json_decode($profile->print_location);
			    	$mockup[$type][$value] = gen_mockup($type, $side->$type, $req->filepng, $value);
	    		}
	    	}

	    	# code...
	    	$csvdata = new CSVDataModel();
	    	$csvdata->design_id = $req->design_id;
	    	$csvdata->brand_name = brand_name();
	    	$csvdata->profile_id = intval($req->selected_profile);
	    	$csvdata->design_month = intval($req->design_month)+1;
	    	$csvdata->item_name = replace_keyword($req->item_name, $req);
	    	$csvdata->item_sku = gen_item_sku(date('y'), $csvdata->design_month, $req->design_id);
	    	$csvdata->bulletpoint_1 = (replace_keyword($req->bulletpoint_1, $req));
	    	$csvdata->bulletpoint_2 = (replace_keyword($req->bulletpoint_2, $req));
	    	$csvdata->bulletpoint_3 = (replace_keyword($req->bulletpoint_3, $req));
	    	$csvdata->bulletpoint_4 = (replace_keyword($req->bulletpoint_4, $req));
	    	$csvdata->bulletpoint_5 = (replace_keyword($req->bulletpoint_5, $req));
	    	$csvdata->searchterm_1 = (replace_keyword($req->searchterm_1, $req));
	    	$csvdata->searchterm_2 = (replace_keyword($req->searchterm_2, $req));
	    	$csvdata->searchterm_3 = (replace_keyword($req->searchterm_3, $req));
	    	$csvdata->searchterm_4 = (replace_keyword($req->searchterm_4, $req));
	    	$csvdata->searchterm_5 = (replace_keyword($req->searchterm_5, $req));
	    	$csvdata->description = (replace_keyword($req->description, $req));
	    	$csvdata->mockup = json_encode($mockup);

		    $validator = Validator::make($csvdata->toArray(), [
		    	'item_name' => [new ValidBannedKeyword],
		        'bulletpoint_1' => [new ValidBannedKeyword],
		        'bulletpoint_2' => [new ValidBannedKeyword],
		        'bulletpoint_3' => [new ValidBannedKeyword],
		        'bulletpoint_4' => [new ValidBannedKeyword],
		        'bulletpoint_5' => [new ValidBannedKeyword],
		        'searchterm_1' => [new ValidBannedKeyword],
		        'searchterm_2' => [new ValidBannedKeyword],
		        'searchterm_3' => [new ValidBannedKeyword],
		        'searchterm_4' => [new ValidBannedKeyword],
		        'searchterm_5' => [new ValidBannedKeyword],
		        'description' => [new ValidBannedKeyword],
		    ]);
		    $errors = $validator->errors()->all();
			foreach ($errors as $message) {
	    		return response()->json(array(
	    			'data' => $message,
	    			'error'=>1
	    		));		    	
			}

	    	$csvdata->save();//save to database

	    	return response()->json(array(
	    		'message'=>'Successful',
	    	));
    	// }catch(\Exception $ex){
    	// 	return response()->json(array(
    	// 		'data' => $ex,
    	// 		'error'=>1
    	// 	));
	    // }
    }

    public function profile(Request $req)
    {
    	# code...
    	$clothing_config = get_color_collection();
    	$title = 'Create new profile';
    	$profile_list = ProfileModel::all();
    	if(isset($req->id)){
    		$profile = ProfileModel::find($req->id);
    	}else{
    		$profile = new ProfileModel();
    		$profile->name = '';
    		$profile->color = '{}';
    		$profile->price = '{}';
    		$profile->print_location = '{}';
    	}

    	if(isset($_POST['name'])){
    		if(!isset($profile)){
	    		$profile = new ProfileModel();
    		}
	    	$profile->name = $_POST['name'];
	    	$profile->color = json_encode($_POST['color']);
	    	$profile->price = json_encode($_POST['price']);
	    	$profile->print_location = json_encode($req->print_location);
	    	// dd(array_keys($_POST['price']));
	    	$type = [];
	    	foreach ($_POST['price'] as $key => $value) {
	    		# code...
	    		if(intval($_POST['price'][$key]>0)){
	    			array_push($type, $key);
	    		}
	    	}
	    	$profile->type = json_encode($type);
	    	$profile->save();
    	}

    	return view('amz_profile_form',[
    		'clothing' => $clothing_config,
    		'title'=>$title,
    		'message_type'=>0,
    		'profile_list' => $profile_list,
    		'current_profile' => $profile
    	]);
    }

    public function keyword(Request $req)
    {
    	# code...
    	$keyword_list = KeywordModel::all()->pluck('main_keyword', 'id');
    	$keyword = KeywordModel::where('id', $req->id)->first();

    	if(!$keyword){
    		$keyword = new KeywordModel();
	    	$keyword->main_keyword = '';
	    	$keyword->bulletpoint_1 = '';
	    	$keyword->bulletpoint_2 = '';
	    	$keyword->bulletpoint_3 = '';
	    	$keyword->bulletpoint_4 = '';
	    	$keyword->bulletpoint_5 = '';
	    	$keyword->searchterm_1 = '';
	    	$keyword->searchterm_2 = '';
	    	$keyword->searchterm_3 = '';
	    	$keyword->searchterm_4 = '';
	    	$keyword->searchterm_5 = '';
	    	$keyword->description = '';
    	}

    	if($req->isMethod('post')){
    		if($req->has('submit')){
		    	$keyword->main_keyword = $req->main_keyword;
		    	$keyword->bulletpoint_1 = remove_banned_keywords($req->bulletpoint_1);
		    	$keyword->bulletpoint_2 = remove_banned_keywords($req->bulletpoint_2);
		    	$keyword->bulletpoint_3 = remove_banned_keywords($req->bulletpoint_3);
		    	$keyword->bulletpoint_4 = remove_banned_keywords($req->bulletpoint_4);
		    	$keyword->bulletpoint_5 = remove_banned_keywords($req->bulletpoint_5);
		    	$keyword->searchterm_1 = remove_banned_keywords($req->searchterm_1);
		    	$keyword->searchterm_2 = remove_banned_keywords($req->searchterm_2);
		    	$keyword->searchterm_3 = remove_banned_keywords($req->searchterm_3);
		    	$keyword->searchterm_4 = remove_banned_keywords($req->searchterm_4);
		    	$keyword->searchterm_5 = remove_banned_keywords($req->searchterm_5);
		    	$keyword->description = remove_banned_keywords($req->description);
		    	$keyword->save();
    		}
    		if($req->has('create')){
    			$data = array(
			    	'main_keyword' => $req->main_keyword,
			    	'bulletpoint_1' => remove_banned_keywords($req->bulletpoint_1),
			    	'bulletpoint_2' => remove_banned_keywords($req->bulletpoint_2),
			    	'bulletpoint_3' => remove_banned_keywords($req->bulletpoint_3),
			    	'bulletpoint_4' => remove_banned_keywords($req->bulletpoint_4),
			    	'bulletpoint_5' => remove_banned_keywords($req->bulletpoint_5),
			    	'searchterm_1' => remove_banned_keywords($req->searchterm_1),
			    	'searchterm_2' => remove_banned_keywords($req->searchterm_2),
			    	'searchterm_3' => remove_banned_keywords($req->searchterm_3),
			    	'searchterm_4' => remove_banned_keywords($req->searchterm_4),
			    	'searchterm_5' => remove_banned_keywords($req->searchterm_5),
			    	'description' => remove_banned_keywords($req->description),
    			);
    			KeywordModel::create($data);
    		}
    	}
    	
    	return view('keyword',[
    		'title' => 'Create new keyword',
    		'keyword_list' => $keyword_list,
    		'keyword' => $keyword,
    		'id' => $req->id,
    		'message_type'=>0
    	]);
    }

	public function exportCSV(Request $req)
	{
		$marketplace = array('Amazon', 'eBay');
		$date = date("Y_m_d_H_i");
		$filter = array('New', 'Exported', 'All');
		$number_per_page = array(20, 50, 100, 200);
		$item_per_page = isset($req->number_per_page)?$number_per_page[$req->number_per_page]:$number_per_page[1];
		$sort_by = array('Created - Desc', 'Created - Asc');

		if($req->isMethod('POST')){
			if($req->export){
				$selectedIDs = array();
				// dd($_POST);
				if($req->selectedIDs!=null)
					$selectedIDs = explode(',', $req->selectedIDs);
				if($req->marketplace==0){//amazon
					$export = new AmazonCSVExport($selectedIDs);
					return Excel::download($export, 'amazon_clothing_'.$date.'.tsv');
				}else if($req->marketplace==1){//ebay
					$export = new EBayCSVExport($selectedIDs);
					return Excel::download($export, 'ebay_clothing_'.$date.'.tsv');
				}
			}
// dd(($req->sort_by==0)?'desc':'asc');
			$csvData = CSVDataModel::where(function($query) use($req){
							$query->orWhere('item_name','LIKE', '%'.$req->keyword.'%');
							$query->orWhere('item_sku','LIKE', '%'.$req->keyword.'%');
						})
						->where('is_exported', intval($req->filter)<2?'=':'>', intval($req->filter)!=2?$req->filter:0)
						->orderBy('created_at', intval($req->sort_by)==0?'desc':'asc')
						->paginate($item_per_page);
			$csvData->appends(['search' => $req->keyword]);
		}

		if(!isset($csvData)){
			$csvData = CSVDataModel::where('id','>',0)
						->orderBy('created_at', 'desc')
						->paginate($item_per_page);
		}
		return view('exportcsv',[
			'title' => 'Export CSV',
			'message_type'=>0,
			'marketplace' => $marketplace,
			'csvdata' => $csvData,
			'filter' => $filter,
			'number_per_page'=>$number_per_page,
			'sort_by' => $sort_by
		]);
	}

    public function clearqueue()
    {
    	# code...
    	$csvdata = CSVDataModel::where('is_exported', false)->get();
    	// $csvdata->truncate();
    	CSVDataModel::where('is_exported', false)->update(array('is_exported' => true));


    	$kw = KeywordModel::all()->pluck('main_keyword', 'id');
    	$prf = ProfileModel::all()->pluck('name','id');

    	return view('amz_tshirt_form', 
    		[
    			'title'=>'Amazon T-Shirt CSV Generator',
    			'subtitle'=>'Queue deleted Successful!',
    			'message_type' => '1',
    			'kws_group_name'=>$kw,
    			'profile_list'=>$prf,
    		]);
    }

    public function keyword_delete(Request $req)
    {
    	# code...
    	$kw = KeywordModel::destroy($req->id);
    	return redirect()->back();
    }

    public function profile_delete(Request $req)
    {
    	# code...
    	$kw = ProfileModel::destroy($req->id);
    	return redirect()->back();    	
    }

    public function fix_db_error()
    {
    	# code...
    	$csvdata = CSVDataModel::where('is_exported', 0)->get();
    	// print_r($csvdata);
    	foreach ($csvdata as $key => $value) {
    		// Update item_sku
    		echo $value->item_sku.' => ';
    		// $value->item_sku = str_replace('06','5',$value->item_sku);
    		# $value->item_sku = preg_replace('/^19/', randomstring(1).'19', $value->item_sku, 1);
    		// $value->item_sku = str_replace('-',randomstring(1),$value->item_sku);
    		if($value->item_sku=='J19P525'){
    			$value->item_sku = 'J19P5'.randomstring(1).'25';
	    		$value->save();
    		}else if($value->item_sku=='W19A5204'){
    			$value->item_sku = 'W19A5'.randomstring(1).'204';
	    		$value->save();
    		}
    		echo $value->item_sku;
    		echo '<br/>';
    		// echo $value->profile_id;
    		// $value->profile_id = 3;
    		// $value->save();
    		// echo '<br/>';
    	}
    }
}
