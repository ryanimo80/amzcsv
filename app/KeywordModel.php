<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordModel extends Model
{
    //
    protected $table = 'keywords';
    protected $fillable = ['main_keyword','bulletpoint_1','bulletpoint_2','bulletpoint_3','bulletpoint_4','bulletpoint_5','searchterm_1','searchterm_2','searchterm_3','searchterm_4','searchterm_5','description'];
}
