<?php

namespace App\Exports;

use App\CSVDataModel;
use App\ProfileModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WishCSVExport implements FromCollection, WithHeadings
{
	protected $market_place_config = array();
	protected $selected_item = array();
    public function __construct($selected_item = array())
    {
    	$this->selected_item = $selected_item;
        $this->market_place_config = array(
        	'shipping'=>6.9,
        	'manufacturer_location'=>'US',//US
        	'handle_time'=>"5-10",//5 days handling time
        	'brand'=>brand_name(),
        	'img_made_in'=>'https://images-na.ssl-images-amazon.com/images/I/71NAb%2BddamL._SX679._SX._UX._SY._UY_.jpg',
        	'img_handle_time'=>'https://images-na.ssl-images-amazon.com/images/I/71BhzMr-zgL._UL1500_.jpg',
        );
    }

    public function headings(): array
	{
		# code...
		$data_header = array("*Parent Unique ID","*Unique ID","UPC","Brand","*Product Name"," Declared Name"," Declared Local Name"," Pieces","Color","Size","*Quantity","*Tags","Description","MSRP","*Price","*Shipping","Shipping time","Product Page","Main Image URL","Extra Image URL","Package Length","Package Width","Package Height","Package Weight","Country Of Origin","Contains Powder","Contains Liquid","Contains Battery"," Contains Metal","Custom Declared Value","Custom HS Code");//31 fields
		return $data_header;
	}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        if(count($this->selected_item)>0){
            $csvdatas = CSVDataModel::whereIn('id', $this->selected_item)->get();
        }else{
            $csvdatas = CSVDataModel::where('is_exported', 0)->get();
        }
        $exportdata = new Collection();

    	foreach ($csvdatas as $key => $value) {
    		# code...
    		$profile = ProfileModel::where('id', $value->profile_id)->first();
    		$type_list = json_decode($profile->color, true);
			# code...
	    	$value_mockup = json_decode($value->mockup, true);
	    	$value_mockup_type = '';
	    	$mockup_url = '';
	    	$type_name = '';
			$price_json = json_decode($profile->price, true);
			$price = 16.77;//default price
			$parent_sku = $value->item_sku;
			$profile_shirt_type = array_keys($type_list);

    		foreach ($value_mockup as $type => $mockup_list) {
				$data = array();
	
    			foreach ($mockup_list as $color => $link) {
    				# code...
    				$data[] = $this->listing_by_profile($value, $type, $color, $profile);
    			}

    			foreach ($data as $key => $data_row) {
    				# code...
					$exportdata->push($data_row);
    			}

    		}

    	}
		return $exportdata;
    }

    public function listing_by_profile($value, $type_name, $color, $profile)
    {
    	# code...
		$type_list = json_decode($profile->color, true);    	
		$price_json = json_decode($profile->price, true);
    	$value_mockup = json_decode($value->mockup, true);
		$price = $price_json[$type_name];
		$type_config = clothing_config($type_name);
		$type_sizes = generate_sizes($type_config['max_size']);
		$sizes = implode(";", array_keys($type_sizes));
		$parent_sku = $value->item_sku;
		$type_mockup = $value_mockup[$type_name][$color];
		$colors = implode(";", $type_list[$type_name]);
		$type_config = clothing_config($type_name);
		$type_sizes = generate_sizes($type_config['max_size']);
		$data_variant = array();
		$i = 0;
		$type = clothing_config($type_name);
		$description = '<h2 style="font-size: 14pt;">PAYMENT</h2><div style="font-size: 14pt;"><span style="font-size: 12pt;">We accept the following payment methods:</span></div><div><div><ul><li><span style="font-size: 12pt;">Paypal,&nbsp;All Major Credit Cards (processed through Paypal, no account necessary)</span></li></ul></div><p><span style="font-size: 12pt;">We do not ship package until the payment is cleared. if you have a problem with payment, please contact us.</span></p><p></p><p style="font-size: 14pt;"><strong><span style="font-size: large;">SHIPPING</span></strong></p></div><div><span style="font-size: 12pt;"><span style="font-size: normal;">We offer </span><strong style="font-size: normal;">FREE</strong><span style="font-size: normal;"> shipping on all orders!</span></span></div><div><p><span style="font-size: 12pt;">Your order will be shipped within 1 business day of receiving payment (Monday-Friday)</span></p><p><span style="font-size: 12pt;">We offer:</span></p><p><span style="font-size: 12pt;"><strong>Domestic Orders:</strong> USPS First Class Mail, Priority Mail and Express options.</span></p><p><span style="font-size: 12pt;"><strong>International Orders:</strong> USPS First Class Mail, Priority Mail and Express options.</span></p><p><span style="font-size: 12pt;">We are not responsible for any foreign customs duties or taxes.</span></p><p><span style="font-size: 12pt;">In the very unlikely event that your item is lost or damaged during post, then WE are responsible and will issue either a full refund or replacement.</span></p><p style="font-size: 14pt;"><strong><span style="font-size: 14pt;">RETURNS</span></strong></p></div><div style="font-size: 14pt;"><em><span style="font-size: 12pt;">If you are not completely satisfied with your purchase, you can return the item within 30 days and get a full refund if the item is defective, damaged, or an error is made on our end. In all other cases a 20% restocking fee will apply.</span></em></div><div><p><span style="font-size: 12pt;">If you would like to exchange the item, please contact us through eBay Messages (20% restocking fee will apply)</span></p><p><span style="font-size: 12pt;">All returns must be initiated through eBay returns.</span></p><p><span style="font-size: 12pt;">If 30 days have gone by since your purchase, unfortunately we can&rsquo;t offer you a refund or exchange. And returned item can be shipped back to customer at customers expense, or will be disposed within 7 days.</span></p><p><span style="font-size: 12pt;">To be eligible for a return, your item must be unused, unwashed and in the same condition that you received it.</span></p><p><span style="font-size: 12pt;">Custom made items (including custom images, writing or other customization requested by customer) are not eligible for a refund or exchange.</span></p><p><span style="font-size: 12pt;">Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.</span></p><p><span style="font-size: 12pt;">If approved, your refund will be processed, and a credit will automatically be applied to original method of payment, within 3-4 days.</span></p><p><span style="font-size: 12pt;">If you haven&rsquo;t received a refund yet, first check your account again. Then contact your credit card company, it may take some time before your refund is officially posted. Next contact your bank. There is often some processing time before a refund is posted.</span></p><p><span style="font-size: 12pt;">If you\'ve done all of this and you still have not received your refund yet, please contact us through eBay messages</span></p><p><span style="font-size: 12pt;">You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable. if you receive a refund, the cost of return shipping will be deducted from your refund.</span></p><p><span style="font-size: 12pt;">Depending on where you live, the time it may take for your exchanged product to reach you, may vary.</span></p><p><span style="font-size: 12pt;">You should consider using a trackable shipping service or purchasing shipping insurance. We don&rsquo;t guarantee that we will receive your returned item.</span></p></div>';
		// $type_mockup .= '|https://images-na.ssl-images-amazon.com/images/I/71NAb%2BddamL._SX679._SX._UX._SY._UY_.jpg|https://images-na.ssl-images-amazon.com/images/I/71BhzMr-zgL._UL1500_.jpg';

		foreach ($type_sizes as $ts_key => $ts_value) {
			# code...
			$child_sku = $value->item_sku.'-'.color_map($color).'-'.$type['short_code'].$ts_key;
	    	$data_variant[] = array(
						$parent_sku.'-'.$type_config['short_code'],$child_sku,"",$this->market_place_config['brand'],ucfirst($value->item_name)." ".$type_config['title']." ".$ts_key,"","","",ucfirst($color),$ts_key,999,"unisex",$description,$price,$price/2,$this->market_place_config['shipping'],$this->market_place_config['handle_time'],"",$type_mockup,$this->market_place_config['img_made_in'].'|'.$this->market_place_config['img_handle_time'],"","","","",$this->market_place_config['manufacturer_location'],"","","","",""
					);
			if($i>=count($type_sizes)-4){
				$price++;
			}
			$i++;
		}
		return $data_variant;
    }
}
