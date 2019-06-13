<?php
namespace App\Exports;

use App\CSVDataModel;
use App\ProfileModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AmazonCSVExport implements FromCollection, WithHeadings
{
    protected $selected_item = array();
    
    public function __construct($selected_item = array())
    {
        $this->selected_item = $selected_item;
    }

    public function headings(): array
    {
    	$data_header = array(
				array("TemplateType=fptcustomcustom","Version=2019.0326","TemplateSignature=U0hJUlQ=","The top 3 rows are for Amazon.com use only. Do not modify or delete the top 3 rows.","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","",""),
				array("Product Type","Seller SKU","Brand Name","Product Name","Product ID","Product ID Type","Item Type Keyword","Color","Color Map","Size","Size Map","Is Adult Product","Material Composition","Material Composition","Material Composition","Material Composition","Material Composition","Material Composition","Material Composition","Material Composition","","Material Composition","Material Composition","Department","Standard Price","Quantity","Shipping-Template","Main Image URL","Other Image URL","Other Image URL","Other Image URL","Parentage","Parent SKU","Relationship Type","Variation Theme","Update Delete","Product Description","Key Product Features","Key Product Features","Key Product Features","Key Product Features","Key Product Features","Search Terms","Search Terms","Search Terms","Search Terms","Search Terms","Fit Type","Shipping Weight","Website Shipping Weight Unit Of Measure","Cpsia Warning","item_weight_unit_of_measure","Item Weight","Is this product a battery or does it utilize batteries?","Batteries are Included","Handling Time"),
				array("feed_product_type","item_sku","brand_name","item_name","external_product_id","external_product_id_type","item_type","color_name","color_map","size_name","size_map","is_adult_product","material_composition1","material_composition2","material_composition3","material_composition3","material_composition5","material_composition6","material_composition7","material_composition8","","material_composition9","material_composition10","department_name","standard_price","quantity","merchant_shipping_group_name","main_image_url","other_image_url1","other_image_url2","other_image_url3","parent_child","parent_sku","relationship_type","variation_theme","update_delete","product_description","bullet_point1","bullet_point2","bullet_point3","bullet_point4","bullet_point5","generic_keywords1","generic_keywords2","generic_keywords3","generic_keywords4","generic_keywords5","fit_type","website_shipping_weight","website_shipping_weight_unit_of_measure","cpsia_cautionary_statement","item_weight_unit_of_measure","item_weight","batteries_required","are_batteries_included","fulfillment_latency"),
		);    	
        return $data_header;
    }

    public function collection()
    {
        if(count($this->selected_item)>0){
            $csvdatas = CSVDataModel::whereIn('id', $this->selected_item)->get();
        }else{
            $csvdatas = CSVDataModel::where('is_exported', 0)->get();
        }
    	$exportdata = new Collection();

    	foreach ($csvdatas as $key => $value) {
    		# code...
    		$profile = ProfileModel::where('id', $value->profile_id)->first();
    		$type_list = json_decode($profile->color);
			# code...
	    	$value_mockup = json_decode($value->mockup);
	    	$value_mockup_type = '';
	    	$mockup_url = '';
	    	$type_name = '';
			$price_json = json_decode($profile->price);
			$price = '';
			$fulfillment_latency = $this->fulfillment_latency();

			$data_parent = array(
				array(//parent
					'',$value->item_sku,$value->brand_name,$value->item_name,
					'','GTIN','','','','','','','','','','','','','','','','','','Unisex',
					$price,'999','Migrated Template',
					$mockup_url,// 27 mockup 1
					'',//28 mockup 1
					'',//29 mockup 1
					'',//30 mockup 1
					'Parent','','',
                    '',// 34 - sizecolor or corlorname
                    'Update',
					minify_html(html_entity_decode($value->description)),$value->bulletpoint_1,$value->bulletpoint_2,$value->bulletpoint_3,$value->bulletpoint_4,$value->bulletpoint_5,$value->searchterm_1,$value->searchterm_2,$value->searchterm_3,$value->searchterm_4,$value->searchterm_5,
					'','0.5','LB','NoWarningApplicable','','','FALSE','FALSE',$fulfillment_latency
				)
			);
			// $exportdata->push($data_parent);
			$is_pushed_parent = false;

    		foreach ($type_list as $type => $color_list) {
    			$data = array();
    			foreach ($color_list as $k => $color) {
    				# code...
    				$data[] = $this->listing_by_profile($value, $type, $color, $profile);
    			}

    			if($is_pushed_parent==false){
	    			$data_parent = $this->update_parent_field($data_parent, $data[0], $type);
					$is_pushed_parent = true;
					$exportdata->push($data_parent);
    			}
    			foreach ($data as $key => $data_row) {
    				# code...
					$exportdata->push($data_row);
    			}

    		}
    	}
        return $exportdata;
    }


    public function update_parent_field($data, $child, $type = 'clothing')
    {
        # Lay gia tri cua child gan nhat gan cho parent
    	# code... 28 is main_image_url field
        #
        $data[0][0] = feed_type($type);//feed type or product type;
        $data[0][6] = item_type($type);//item type;
		$data[0][3] = is_mug_type($type)?$child[0][3]:substr($child[0][3], 0, -2).'';//item_name;
		$data[0][27] = $child[0][27];//set parent mockup = unisex mockup;    
		$data[0][24] = $child[0][24];//set parent mockup = unisex mockup;    
		$data[0][28] = $child[0][28];//set parent mockup = unisex mockup;    
		$data[0][29] = $child[0][29];//set parent mockup = unisex mockup;    
		$data[0][30] = $child[0][30];//set parent mockup = unisex mockup;    
        $data[0][34] = $child[0][34];// neu la mug thi la colorname, clothing thi la sizecolor
		return $data;	
    }

    public function listing_by_profile($value, $type, $color, $profile)
    {
        $clothing_type = clothing_config($type);
        $short_code = $clothing_type['short_code'];
        $max_size = $clothing_type['max_size'];

        if(is_mug_type($type)){
            $sizes = array(
                $type => array($short_code, ucfirst($type))
            );
        }else{
            $sizes = array();
            foreach (generate_sizes($max_size) as $key => $v) {
                # code...
                $sizes[$key] = array($short_code.$key, $v);
            }            
        }
    	$data = $this->list_size($sizes, $value, $type, $color, $profile);
    	return $data;
    }

    public function list_size($sizes, $value, $type, $color, $profile)
    {
    	# code...
    	$value_mockup = json_decode($value->mockup);
    	$value_mockup_type = $value_mockup->$type;
    	// $mockup_url = config('filesystems.disks.onedrive.url').$value_mockup_type->$color;
    	$mockup_url = $value_mockup_type->$color;
    	$type_name = ucfirst($color).' '.type_map($type);
		$price_json = json_decode($profile->price);
		$price = $price_json->$type;
		$fulfillment_latency = $this->fulfillment_latency();
		$data = array();
		$i = 0;
		$banner_url1 = 'https://images-na.ssl-images-amazon.com/images/I/71NAb%2BddamL._SX679._SX._UX._SY._UY_.jpg';
		$banner_url2 = 'https://images-na.ssl-images-amazon.com/images/I/71BhzMr-zgL._UL1500_.jpg';
		$print_location = json_decode($profile->print_location);
		$print_location = $print_location->$type;
		// $mockup_blank_side = $this->get_blank_mockup($type, $color, $print_location==0?1:0 );

    	foreach ($sizes as $size_name=>$size_map) {
    		# code...
			$child_sku = $value->item_sku.'-'.color_map($color).'-'.$size_map[0];
			$data[] = array(//child
							feed_type($type),$child_sku,$value->brand_name,

                            html_entity_decode($value->item_name).' '.type_map($type).(is_mug_type($type)?'':' '.$size_name),

							'','GTIN',item_type($type), $type_name, ucfirst($color),

                            is_mug_type($type)?'':$size_name, // neu la mug thi de trong size
                            is_mug_type($type)?'':$size_map[1], // neu la mug thi de trong size

                            'FALSE','','','','','','','','','','','','Unisex',
							$price,'999','Migrated Template',

							is_mug_type($type)?(explode('|',$mockup_url)[0]):$mockup_url, // neu la mug thi mockup co 2 gia tri
							is_mug_type($type)?(explode('|',$mockup_url)[1]):get_size_chart($type),
                           
							$banner_url1,
							$banner_url2,
							'Child',$value->item_sku,'Variation',

                            is_mug_type($type)?'ColorName':'SizeColor',
                            
                            'Update',
							minify_html(html_entity_decode($value->description)),html_entity_decode($value->bulletpoint_1),($value->bulletpoint_2),($value->bulletpoint_3),($value->bulletpoint_4),($value->bulletpoint_5),$value->searchterm_1,$value->searchterm_2,$value->searchterm_3,$value->searchterm_4,$value->searchterm_5,
							'','0.5','LB','NoWarningApplicable','','','FALSE','FALSE',$fulfillment_latency
						);
			if($i>=count($sizes)-4){
				$price++;
			}
			$i++;
    	}
    	return $data;    	
    }

    function fulfillment_latency()
    {
    	return 5; //default fulfillment_latency
    }



    function get_blank_mockup($type, $color, $print_location)
    {
    	# code...
    	$blank_mockup = array(
    		'unisex-t-shirt' => array(
    			'black' => array(
    				'/JPG/0-front/black-t-shirt.jpg',//front
    				'/JPG/0-back/black-t-shirt.jpg'//back
    			),
    			'navy' => array(
    				'/JPG/0-front/navy-t-shirt.jpg',//front
    				'/JPG/0-back/navy-t-shirt.jpg'//back
    			),
    		),
    		'tank-top' => array(
    			'black' => array(
    				'/JPG/0-front/black-tank-top.jpg',//front
    				'/JPG/0-back/black-tank-top.jpg'//back
    			),
    			'navy' => array(
    				'/JPG/0-front/navy-tank-top.jpg',//front
    				'/JPG/0-back/navy-tank-top.jpg'//back
    			),
    		),    		
    		'hoodie' => array(
    			'black' => array(
    				'/JPG/0-front/black-hoodie.jpg',//front
    				'/JPG/0-back/black-hoodie.jpg'//back
    			),
    			'navy' => array(
    				'/JPG/0-front/navy-hoodie.jpg',//front
    				'/JPG/0-back/navy-hoodie.jpg'//back
    			),
    		),    	    		
    	);
    	return '';
    	// return config('filesystems.disks.onedrive.url').$blank_mockup[$type][$color][$print_location];
    }
}