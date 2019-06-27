<?php
use Illuminate\Support\Str;	

include_once('teezily_helper.php');
include_once('minify_helper.php');

function clothing_config($type='')
{
	if($type!=''){
		$product = \Config::get('amzcsv.product.'.$type);
		return $product;
	}
// 
	return \Config::get('amzcsv.product');
}

function  get_print_sizes($location='front')
{
	$print_location = array();
	$clothing_config = clothing_config();
	foreach ($clothing_config as $key => $value) {
		# code...
		if(isset($value['print_location'][$location])){
			$print_location[ $value['name'] ] = $value['print_location'][$location];
		}
	}
	return $print_location;
}

function is_mug_type($type)
{
	if(in_array($type, array('mug11oz', 'mug15oz'))){//
		return true;
	}
	return false;
}

if(!function_exists('gen_mockup_front_side'))
{
	function gen_mockup_front_side($type, $design_file, $color='', $title='')
	{
		$time = $title.'-'.time();
		$print_location = get_print_sizes('front');
		$mockup_file = 'front-'.$type.'.jpg';

		if(file_exists($design_file)){
			$design = $design_file;			
		}else{
			$design = public_path().'/'.$design_file;			
		}

		if(is_mug_type($type)){
			$resize_design = array(800, 968); // resize png theo mockup cua mua
		}else{
			$resize_design = array(580, 702); // resize png theo mockup cua ao
		}

		$blank_mockup = "/blank-mockup/$color/".$mockup_file;
		$path = Storage::disk('onedrive')->getAdapter()->getPathPrefix();
		$save_mockup = $path.'/mockup/'.$color.'/'.$time.'.jpg';

	    $img = Image::make(Storage::disk('local')->get($blank_mockup));
	    $artwork = Image::make($design)->resize($resize_design[0], $resize_design[1]);
	    $img->insert($artwork, 'top-left', $print_location[$type][0], $print_location[$type][1]);

	    if (!File::exists( $path.'/mockup/'.$color )) {
	        File::makeDirectory($path.'/mockup/'.$color, 0755, true);
	    }

	    $img->save($save_mockup);
	    return \Config::get('amzcsv.mockup.public_url').'/JPG/mockup/'.$color.'/'.$time.'.jpg';
	}	
}

if(!function_exists('gen_mockup_back_side'))
{
	function gen_mockup_back_side($type, $design_file, $color='', $title='')
	{
		$time = $title.'-'.time();
		$print_location = get_print_sizes('back');		
		$mockup_file = 'back-'.$type.'.jpg';
		
		if(file_exists($design_file)){
			$design = $design_file;			
		}else{
			$design = public_path().'/'.$design_file;			
		}

		if(is_mug_type($type)){
			$resize_design = array(800, 968); // resize png theo mockup cua mua
		}else{
			$resize_design = array(630, 756); // resize png theo mockup ao
		}

		$blank_mockup = "/blank-mockup/$color/".$mockup_file;
		$path = Storage::disk('onedrive')->getAdapter()->getPathPrefix();
		$save_mockup = $path.'/mockup/'.$color.'/'.$time.'.jpg';
	    $img = Image::make(Storage::disk('local')->get($blank_mockup));
	    $artwork = Image::make($design)->resize($resize_design[0], $resize_design[1]);
	    $img->insert($artwork, 'top-left', $print_location[$type][0], $print_location[$type][1]);

	    if (!File::exists( $path.'/mockup/'.$color )) {
	        File::makeDirectory($path.'/mockup/'.$color, 0755, true);
	    }

	    $img->save($save_mockup);
	    return \Config::get('amzcsv.mockup.public_url').'/JPG/mockup/'.$color.'/'.$time.'.jpg';
	}	
}


if(!function_exists('gen_mockup')){
	function gen_mockup($type, $side, $design_file, $color, $title)
	{
		if(is_mug_type($type)){
			$filepath1 = gen_mockup_front_side($type, $design_file, $color, $title);
			$filepath2 = gen_mockup_back_side($type, $design_file, $color, $title);
			return $filepath1.'|'.$filepath2;// mockup mat truoc|mockup mat sau
		}else{
			if($side==0){
				return gen_mockup_front_side($type, $design_file, $color, $title);
			}else{
				return gen_mockup_back_side($type, $design_file, $color, $title);
			}			
		}
	}
}

function generate_png_mockup($file_png, $profile, $title)
{
	/**
	* Generate mockup
	*
	*/
	$mockup = array();
	$colors = json_decode($profile->color);
	
	foreach ($colors as $type => $color) {
		# code...
		$mockup[$type] = array();
		foreach ($color as $value) {
			# code...
			$side = json_decode($profile->print_location);
	    	$mockup[$type][$value] = gen_mockup($type, $side->$type, $file_png, $value, $title);
		}
	}
	return $mockup;
}
function randomstring($length)
{
	// $string = "";
	// $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	// for($i=1;$i<=$len;$i++)
	// 	$string.=substr($chars,rand(0,strlen($chars)),1);
	// return $string;
	return substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);	
}

if(!function_exists('gen_item_sku')){
	function gen_item_sku($year='', $month='', $design_id)
	{
		# code...
		// $month = intval($year)>0?$month:date('y');
		$month = intval($month)>0?$month:date('n');
		$sku = randomstring(1).$year.randomstring(1).$month.randomstring(1).$design_id;
		return strtoupper($sku);
	}
}

function color_map($c)
{
	$color = array(
		'black' => 'BL',
		'navy'	=> 'NV',
		'white' => 'WH',
		'charcoal' => "CC",
		'ash' => 'AS',
		'royal' => 'RY',
		'forest' => 'FR',
		'red' => 'RD', 
		'white' => 'WT',
		'sport grey' => 'SG',
		'heather grey' => 'HG',
		'kelly' => 'KL'
	);
	return $color[$c];
}

function type_map($t){
	$type = array();
	// dd(clothing_config());
	foreach (clothing_config() as $key => $value) {
		# code...
		$type[ $value['name'] ] = $value['title'];
	}
	return $type[$t];
}

function generate_sizes($size_shortcode, $type = 'clothing')
{
	$size = array(
		'S' => 'Small',
		'M' => 'Medium',
		'L' => 'Large',
		'XL' => 'X-Large',
		'2XL' => '2XL-Large',
		'3XL' => '3XL-Large',
		'4XL' => '4XL-Large',
		'5XL' => '5XL-Large',
		'6XL' => '6XL-Large',
	);
	$ret = array();
	$is_found_max_size = false;
	foreach ($size as $key => $value) {
		# code...
		$ret[$key] = $value;
		if($key == $size_shortcode){
			return $ret;
		}
	}		
}

function brand_name()
{
 	$brand = 'TheOceanPub Designs';
	return $brand;   	
}

function get_color_collection($type = '')
{
	$clothing_color_config = array();
	$clothing_config = clothing_config();
	foreach ($clothing_config as $key => $value) {
		# code...
		if(isset($value['print_location'])){
			$clothing_color_config[] = $value;
		}
	}	

	if(isset($type)){
		foreach ($clothing_color_config as $key => $value) {
			# code...
			if($value['name'] == $type)
			{
				return $value['color'];
			}
		}
	}

	return $clothing_color_config;
}


function remove_banned_keywords($text)
{
	# code...
	# $text = preg_replace("/[^a-zA-Z0-9 \,\.\[\]\-\!\'\"']/", "", $text);
	// $text = ucfirst(str_replace(get_banned_keywords(), "", strtolower($text)));
	return $text;
}

function replace_keyword($text, $keyword="", $keyword1="", $keyword2="")
{
	# code...
	// allow only letters
	// $text = remove_banned_keywords($text);
	$new_text = trim(str_replace('[keyword]', strtoupper($keyword), $text));
	$new_text = trim(str_replace('[keyword1]', strtoupper($keyword1), $new_text));
	$new_text = trim(str_replace('[keyword2]', strtoupper($keyword2), $new_text));
	$new_text = ucfirst(replace_brand($new_text));
	return $new_text;
}

function replace_brand($text){
	$new_text = trim(str_replace('[brand]', brand_name(), $text));	
	return $new_text;	
}

function get_banned_keywords()
{
	$banned_keywords = strtoupper(\Storage::disk('local')->get('keywordbanned.txt'));
	$banned_keywords = explode("\n", $banned_keywords);
	$banned_keywords = array_map('trim', $banned_keywords);
	return $banned_keywords;
}

function valid_date_bulletpoint($text)
{
	$max_length_bullet_point = 500;
	if(strlen($text)){
		return false;
	}
	return true;
}

function item_type($type)
{
    if(is_mug_type($type))
        $type = 'novelty-coffee-mugs';
    else
        $type = 'fashion-tshirts';
	return $type;
}

function feed_type($type)
{
    if(is_mug_type($type))
        $type = 'kitchen';
    else
        $type = 'shirt';
    return $type;
}

function extract_numer($string)
{
	return preg_replace('/[^0-9]/', '', $string);	
}

function get_size_chart($type='')
{
	# code...
	// dd(clothing_config($type)['size_chart']);
	return optional(clothing_config($type))['size_chart'];
}

function storage_png_path($file="")
{
	if(file_exists($file))
		return $file;
	$folder_name = storage_path().'/artwork/';
	if(!empty($file))
		return $folder_name.'/'.$file; 
	return $folder_name;
}