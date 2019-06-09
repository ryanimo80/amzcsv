<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KeywordModel;
use App\ProfileModel;
use App\CSVDataModel;
use Excel;
use App\Exports\AmazonCSVExport;
use App\Exports\EbayCSVExport;
use App\Exports\WishCSVExport;
use App\Rules\ValidBannedKeyword;

class CSVController extends Controller
{
    //

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

	    	/**
	    	* Generate mockup
	    	*
	    	*/
	    	$mockup = generate_png_mockup($req->filepng, $colors);

	    	$csvdata->mockup = json_encode($mockup);

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

    public function edit(Request $req)
    {
    	# code...
    	$csv = CSVDataModel::find($req->id);
    	$profile = ProfileModel::where('id', $csv->profile_id)->first();

    	if($req->isMethod('POST')){
    		if($req->get('updatemk')){
		        if($req->hasfile('file_png'))
		         {
		            $file = $req->file('file_png');
	                $name = $file->getClientOriginalName();
	                $path = '/files/'.time();
	                $file->move(public_path().$path, $name);

	                // $csvdata = CSVDataModel::where('id', $req->id)->first();
			    	$mockup = generate_png_mockup($path.'/'.$name, $profile);
	    			$csv->mockup = json_encode($mockup);
	    			// dd($csv->mockup);
			    	$csv->save();
		         }
    		}

    		if($req->get('updatekw')){
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
    	}

    	return view('edit_csv',[
    		'title'=>'Edit ',
    		'message_type' =>0,
    		'csv' => $csv
    	]);
    }


	public function exportCSV(Request $req)
	{
		$marketplace = array('Amazon', 'eBay', 'Wish');
		$date = date("Y_m_d_H_i");
		$filter = array('New', 'Exported', 'All');
		$number_per_page = array(20, 50, 100, 200);
		$item_per_page = isset($req->number_per_page)?$number_per_page[$req->number_per_page]:$number_per_page[1];
		$sort_by = array('Created - Desc', 'Created - Asc');

		if($req->isMethod('POST')){
			if($req->export){
				$selectedIDs = array();

				if($req->selectedIDs!=null)
					$selectedIDs = explode(',', $req->selectedIDs);

				if($req->marketplace==0){//amazon
					$export = new AmazonCSVExport($selectedIDs);
					return Excel::download($export, 'amazon_clothing_'.$date.'.tsv');
				}else if($req->marketplace==1){//ebay
					$export = new EBayCSVExport($selectedIDs);
					return Excel::download($export, 'ebay_clothing_'.$date.'.tsv');
				}else if($req->marketplace==2){//ebay
					$export = new WishCSVExport($selectedIDs);
					return Excel::download($export, 'wish_clothing_'.$date.'.tsv');
				}
			}

			$csvData = CSVDataModel::where(function($query) use($req){
						$keyword = str_replace('*','%', $req->keyword);
							$query->orWhere('item_name','LIKE', '%'.$keyword.'%');
							$query->orWhere('item_sku','LIKE', '%'.$keyword.'%');
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

}
