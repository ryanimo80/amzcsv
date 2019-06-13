<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KeywordModel;
use App\Rules\ValidBannedKeyword;
use Validator;

class KeywordController extends Controller
{
    //
    public function show(Request $req)
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
		    $validator = Validator::make($req->all(), [
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
		    if($validator->fails()){
				\Session::flash('errors', $validator->errors());		    	
		    	//return back()->withErrors($validator->errors());
		    }

		    $validator = Validator::make($req->all(), [
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
		    if($validator->fails()){
				\Session::flash('errors', $validator->errors());
		    	//return back()->withErrors($validator->errors());
		    }



    		if($req->has('submit')){
		    	$keyword->main_keyword = $req->main_keyword;
		    	$keyword->bulletpoint_1 = ($req->bulletpoint_1);
		    	$keyword->bulletpoint_2 = ($req->bulletpoint_2);
		    	$keyword->bulletpoint_3 = ($req->bulletpoint_3);
		    	$keyword->bulletpoint_4 = ($req->bulletpoint_4);
		    	$keyword->bulletpoint_5 = ($req->bulletpoint_5);
		    	$keyword->searchterm_1 = ($req->searchterm_1);
		    	$keyword->searchterm_2 = ($req->searchterm_2);
		    	$keyword->searchterm_3 = ($req->searchterm_3);
		    	$keyword->searchterm_4 = ($req->searchterm_4);
		    	$keyword->searchterm_5 = ($req->searchterm_5);
		    	$keyword->description = ($req->description);
	    		if(!$validator->fails()) 
	    			$keyword->save();
    		}
    		if($req->has('create')){
    			$data = array(
			    	'main_keyword' => $req->main_keyword,
			    	'bulletpoint_1' => ($req->bulletpoint_1),
			    	'bulletpoint_2' => ($req->bulletpoint_2),
			    	'bulletpoint_3' => ($req->bulletpoint_3),
			    	'bulletpoint_4' => ($req->bulletpoint_4),
			    	'bulletpoint_5' => ($req->bulletpoint_5),
			    	'searchterm_1' => ($req->searchterm_1),
			    	'searchterm_2' => ($req->searchterm_2),
			    	'searchterm_3' => ($req->searchterm_3),
			    	'searchterm_4' => ($req->searchterm_4),
			    	'searchterm_5' => ($req->searchterm_5),
			    	'description' => ($req->description),
    			);
	    		if(!$validator->fails())
					KeywordModel::create($data);
    		}
    	}
    	return view('keyword',[
    		'title' => 'Create new keyword',
    		'keyword_list' => $keyword_list,
    		'keyword' => $keyword,
    		'id' => $req->id,
    		'message_type'=>0,
    	]);
    }    


    public function keyword_delete(Request $req)
    {
    	# code...
    	$kw = KeywordModel::destroy($req->id);
    	return redirect()->back();
    }


    public function kwjson(Request $req)
    {
    	$kw = array();
		$kw = KeywordModel::find(array('id'=>$req->id));
    	return response()->json($kw);    	
    }

}
