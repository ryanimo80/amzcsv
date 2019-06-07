<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    //
    protected $table = 'users';

    public static function get_all_user()
    {
    	return UserModel::all();
    }
}
