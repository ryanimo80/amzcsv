<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeezilyController extends Controller
{
    //
    public function index()
    {
    	# code...
    }

    public function scan(Request $req)
    {
    	# code...
    	if($req->isMethod('POST')){
	    	$links = explode("\n", $req->links);
	    	foreach ($links as $key => $value) {
	    		# code...
	    		$content = get_content('https://www.teezily.com'.$value);
	    		print_r(teezily_get_photo($content));
	    		$photo_download = Image::make()->resize(1080, 1215);
	    		sleep(3);
	    	}
    	}
    	return view('teezily_scan',[
    		'title' => 'Teezily Scan',
    		'message' => '',
    		'message_type' => 0
    	]);
    }

    public function ajax_scan(Request $req)
    {
    	// dd($req->link);
    	if($req->isMethod('POST')){
	    	$ret = teezily_scan($req->link);
	    	array_push($ret, array('link'=>$req->link));
	    	return response()->json($ret);
    	}
    	return response()->json(array());
    }
}
