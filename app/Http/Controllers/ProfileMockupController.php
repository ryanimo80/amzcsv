<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KeywordModel;
use App\ProfileModel;
class ProfileMockupController extends Controller
{
    //
    public function show(Request $req)
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


    public function profile_delete(Request $req)
    {
    	# code...
    	$kw = ProfileModel::destroy($req->id);
    	return redirect()->back();    	
    }
}
