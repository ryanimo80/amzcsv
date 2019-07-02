<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomcatSkuSettingModel extends Model
{
    //
    protected $table = 'customcat_sku_setting';    
    protected $fillable = ['short_code','color','size_sku'];
}
