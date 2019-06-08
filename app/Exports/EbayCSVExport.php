<?php

namespace App\Exports;

use App\CSVDataModel;
use App\ProfileModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EbayCSVExport implements FromCollection, WithHeadings
{
	protected $eBay_config = array();
	protected $selected_item = array();
    public function __construct($selected_item = array())
    {
    	$this->selected_item = $selected_item;
        $this->eBay_config = array(
        	'paypal_email' => 'info@theoceanpub.com',
        	'shipping_profile_name'=>'customcat_shipping_policy',//PF_FR_GARMENT_DEFAULT
        	'return_profile_name'=>'PRINTFUL_RETURN_POLICY',
        	'payment_profile_name'=>'PayPal:Immediate pay',
        	'clothing_category_id'=>155193,
        	'clothing_condition_id'=>1500,//new without tags
        	'manufacturer_location'=>48324,//customcat inc. states zipcode
        	'handle_time'=>5,//5 days handling time
        );
    }

    public function headings(): array
	{
		# code...
		$data_header = array("*Action(SiteID=US|Country=US|Currency=USD|Version=745|CC=UTF-8)","CustomLabel","*Category","StoreCategory","*Title","Subtitle","Relationship","RelationshipDetails","*ConditionID","C:Size","*C:Brand","C:MPN","C:Style","C:Theme","C:Color","C:Material","C:Modified Item","C:Modification Description","C:Personalized","C:California Prop 65 Warning","C:Country/Region of Manufacture","","PicURL","GalleryType","*Description","*Format","*Duration","*StartPrice","BuyItNowPrice","*Quantity","PayPalAccepted","PayPalEmailAddress","ImmediatePayRequired","PaymentInstructions","*Location","ShippingType","ShippingService-1:Option","ShippingService-1:Cost","ShippingService-2:Option","ShippingService-2:Cost","*DispatchTimeMax","PromotionalShippingDiscount","ShippingDiscountProfileID","DomesticRateTable","*ReturnsAcceptedOption","ReturnsWithinOption","RefundOption","ShippingCostPaidByOption","AdditionalDetails","UseTaxTable","ShippingProfileName","ReturnProfileName","PaymentProfileName");
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
			$description = '<h2 style="font-size: 14pt;">PAYMENT</h2><div style="font-size: 14pt;"><span style="font-size: 12pt;">We accept the following payment methods:</span></div><div><div><ul><li><span style="font-size: 12pt;">Paypal,&nbsp;All Major Credit Cards (processed through Paypal, no account necessary)</span></li></ul></div><p><span style="font-size: 12pt;">We do not ship package until the payment is cleared. if you have a problem with payment, please contact us.</span></p><p></p><p style="font-size: 14pt;"><strong><span style="font-size: large;">SHIPPING</span></strong></p></div><div><span style="font-size: 12pt;"><span style="font-size: normal;">We offer </span><strong style="font-size: normal;">FREE</strong><span style="font-size: normal;"> shipping on all orders!</span></span></div><div><p><span style="font-size: 12pt;">Your order will be shipped within 1 business day of receiving payment (Monday-Friday)</span></p><p><span style="font-size: 12pt;">We offer:</span></p><p><span style="font-size: 12pt;"><strong>Domestic Orders:</strong> USPS First Class Mail, Priority Mail and Express options.</span></p><p><span style="font-size: 12pt;"><strong>International Orders:</strong> USPS First Class Mail, Priority Mail and Express options.</span></p><p><span style="font-size: 12pt;">We are not responsible for any foreign customs duties or taxes.</span></p><p><span style="font-size: 12pt;">In the very unlikely event that your item is lost or damaged during post, then WE are responsible and will issue either a full refund or replacement.</span></p><p style="font-size: 14pt;"><strong><span style="font-size: 14pt;">RETURNS</span></strong></p></div><div style="font-size: 14pt;"><em><span style="font-size: 12pt;">If you are not completely satisfied with your purchase, you can return the item within 30 days and get a full refund if the item is defective, damaged, or an error is made on our end. In all other cases a 20% restocking fee will apply.</span></em></div><div><p><span style="font-size: 12pt;">If you would like to exchange the item, please contact us through eBay Messages (20% restocking fee will apply)</span></p><p><span style="font-size: 12pt;">All returns must be initiated through eBay returns.</span></p><p><span style="font-size: 12pt;">If 30 days have gone by since your purchase, unfortunately we can&rsquo;t offer you a refund or exchange. And returned item can be shipped back to customer at customers expense, or will be disposed within 7 days.</span></p><p><span style="font-size: 12pt;">To be eligible for a return, your item must be unused, unwashed and in the same condition that you received it.</span></p><p><span style="font-size: 12pt;">Custom made items (including custom images, writing or other customization requested by customer) are not eligible for a refund or exchange.</span></p><p><span style="font-size: 12pt;">Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.</span></p><p><span style="font-size: 12pt;">If approved, your refund will be processed, and a credit will automatically be applied to original method of payment, within 3-4 days.</span></p><p><span style="font-size: 12pt;">If you haven&rsquo;t received a refund yet, first check your account again. Then contact your credit card company, it may take some time before your refund is officially posted. Next contact your bank. There is often some processing time before a refund is posted.</span></p><p><span style="font-size: 12pt;">If you\'ve done all of this and you still have not received your refund yet, please contact us through eBay messages</span></p><p><span style="font-size: 12pt;">You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable. if you receive a refund, the cost of return shipping will be deducted from your refund.</span></p><p><span style="font-size: 12pt;">Depending on where you live, the time it may take for your exchanged product to reach you, may vary.</span></p><p><span style="font-size: 12pt;">You should consider using a trackable shipping service or purchasing shipping insurance. We don&rsquo;t guarantee that we will receive your returned item.</span></p></div>';			
    		foreach ($value_mockup as $type => $mockup_list) {
				$price = $price_json[$type];
				$type_config = clothing_config($type);
				$type_sizes = generate_sizes($type_config['max_size']);
				$sizes = implode(";", array_keys($type_sizes));
				$colors = implode(";", array_map("ucfirst", $type_list[$type]));
				$type_mockup = head(array_values($value_mockup[$type]));
				$type_mockup .= '|https://images-na.ssl-images-amazon.com/images/I/71NAb%2BddamL._SX679._SX._UX._SY._UY_.jpg|https://images-na.ssl-images-amazon.com/images/I/71BhzMr-zgL._UL1500_.jpg';
    			$data_parent = array(
					array(//parent
						"Add",$parent_sku.'-'.$type_config['short_code'],$this->eBay_config['clothing_category_id'],"",ucfirst($value->item_name)." ".$type_config['title'],"","","Size=".$sizes."|Color=".$colors,$this->eBay_config['clothing_condition_id'],"","Unbranded","Does Not Apply","Basic Tee","","","100% Cotton","No","","","","United States","",$type_mockup,"",$description,"FixedPrice","GTC",$price,"","","1",$this->eBay_config['paypal_email'],"1","",$this->eBay_config['manufacturer_location'],"Flat","","","","",$this->eBay_config['handle_time'],"","","","ReturnsNotAccepted","","","","","",$this->eBay_config['shipping_profile_name'],$this->eBay_config['return_profile_name'],$this->eBay_config['payment_profile_name']
					)
				);
				$exportdata->push($data_parent);
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
		$type_mockup .= '|https://images-na.ssl-images-amazon.com/images/I/71NAb%2BddamL._SX679._SX._UX._SY._UY_.jpg|https://images-na.ssl-images-amazon.com/images/I/71BhzMr-zgL._UL1500_.jpg';

		foreach ($type_sizes as $ts_key => $ts_value) {
			# code...
			$child_sku = $value->item_sku.'-'.color_map($color).'-'.$type['short_code'].$ts_key;
			$clothing_type = "Basic Tee";
	    	$data_variant[] = array(
						"Add",$child_sku,$this->eBay_config['clothing_category_id'],"",ucfirst($value->item_name)." ".$type_config['title']." ".$ts_key,"","Variation","Size=".$ts_key."|Color=".ucfirst($color),$this->eBay_config['clothing_condition_id'],"","Unbranded","Does Not Apply",$clothing_type,"","","100% Cotton","No","","","","United States","",$type_mockup,"",$description,"FixedPrice","GTC",$price,"","1","1",$this->eBay_config['paypal_email'],"1","",$this->eBay_config['manufacturer_location'],"Flat","","","","",$this->eBay_config['handle_time'],"","","","ReturnsNotAccepted","","","","","",$this->eBay_config['shipping_profile_name'],$this->eBay_config['return_profile_name'],$this->eBay_config['payment_profile_name']
					);
			if($i>=count($type_sizes)-4){
				$price++;
			}
			$i++;
		}
		return $data_variant;
    }
}
