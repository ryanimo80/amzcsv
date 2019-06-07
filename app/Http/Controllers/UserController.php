<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\UserModel;

class UserController extends Controller
{
   public function index() {
      // echo 'index';
   		$users = UserModel::get_all_user();
   		return view('greeting', ['name'=>'admin', 'list_user'=>$users]);
   }
   public function create(Request $request) {
      echo 'create';
   }
   public function store(Request $request) {
      $username = $request->username;
      $password = $request->password;

      $new_user = new UserModel();
      $new_user->username = $username;
      $new_user->password = $password;

      $path = $request->file('avatar')->store('avatars');
      $new_user->avatar = $path;
      $new_user->save();
      return view('greeting', ['name'=>'admin', 'list_user'=>$new_user->get_all_user()]);
   }
   public function show($id) {
      echo 'show';
   }
   public function edit($id) {
      echo 'edit';
   }
   public function update(Request $request, $id) {
      echo 'update';
   }
   public function destroy($id) {
      echo 'destroy';
   }

}
