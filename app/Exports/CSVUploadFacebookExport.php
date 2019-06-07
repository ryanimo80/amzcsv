<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;

class CSVUploadFacebookExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $collection;
    
    public function __construct()
    {
    	$this->collection = array();
    }

    public function collection()
    {
        //
    }

    public function array(): array
    {
        return $this->collection;
    }

    public function push($value)
    {
    	# code...
    	if($value==false){
    		return;
    	}
    	array_push($this->collection, $value);
    }
}
