<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KeywordModel;

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


    public function keyword_delete(Request $req)
    {
    	# code...
    	$kw = KeywordModel::destroy($req->id);
    	return redirect()->back();
    }


    public function kwjson($id)
    {
    	$kw = array();
    	if(intval($id)>0){
    		$kw = KeywordModel::find(array('id'=>$id));
    	}
    	return response()->json($kw);    	
    }

}
