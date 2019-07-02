<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\ProfileModel;
use App\CustomcatSkuSettingModel;

class OrderDeskInventoryCSV implements FromCollection, WithHeadings
{
	protected $current_csvdata;
	protected $current_profile;
	protected $printer_vendor = 'customcat';

	public function __construct($csvdata, $profile='')
	{
		# code...
		$this->current_csvdata = $csvdata;
		$this->profile = $profile;
	}

    public function headings(): array
	{
		# code...
		$data_header = array("Product Name","Product SKU","Printer Name","Printer Product Code","Artwork URL","Print Location");

		return $data_header;
	}	

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $exportdata = new Collection();
        $profile_type_color = json_decode($this->get_profile()->color);
        foreach ($profile_type_color as $type => $color_value) {
        	$type_config = clothing_config($type);
	        	foreach ($color_value as $color) {
		        	$product_size = generate_sizes($type_config['max_size'], $type);;
		        	$customcat_sku = CustomcatSkuSettingModel::where('short_code', $type_config['short_code'])
		        						->where('color', $color)->first();
		        	$list_cc_sku = explode("\n", $customcat_sku['size_sku']);
		        	$title = $this->get_csvdata_title();
		        	$print_location = $this->get_print_location($type);
		        	$i=0;
		        	foreach ($product_size as $size_code => $value) {
			        	$sku = $this->get_csvdata_sku()
			        			."-".color_map($color)
			        			."-".$type_config['short_code']
			        			.(is_mug_type($type)?"":$size_code);
		        		$data = array(
		        			$title." ".$color." ".$type." ".$size_code,
		        			$sku,
		        			$this->printer_vendor,
		        			trim(optional($list_cc_sku)[$i]),
		        			$this->get_csvdata_dropbox_url(),
		        			$print_location
		        		);
		        		if(!empty(trim(optional($list_cc_sku)[$i])))
		        			$exportdata->push($data);
		        		$i++;
		       		}
	        	}        		

        }
        return $exportdata;
    }

    public function get_csvdata_dropbox_url()
    {
    	return $this->current_csvdata->dropbox_shared_url;
    }

    public function get_csvdata_title()
    {
    	return $this->current_csvdata->item_name;
    }

    public function get_csvdata_sku()
    {
    	return $this->current_csvdata->item_sku;
    }

    public function get_print_location($type='')
    {
    	$print_location = json_decode($this->current_profile->print_location, true);
    	$print_location = intval($print_location[$type])==1?'back':'front';
    	return $print_location;
    }

    public function set_csvdata($value='')
    {
    	# code...
    	$this->current_csvdata = $value;
    }

    public function get_csvdata()
    {
    	# code...
    	return $this->current_csvdata;
    }

    public function set_profile($profile)
    {
        # code...
        $this->current_profile = $profile;
    }

    public function get_profile($profile_id="")
    {
        # code...
        if($this->current_profile == null)
            $profile = ProfileModel::where('id', $profile_id)->first();
        else
            $profile = $this->current_profile;

        return $profile;
    }    
}
