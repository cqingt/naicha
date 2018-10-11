<?php

namespace App\Http\Controllers\Clerk;

use Validator;
use App\Http\Models\User;
use App\Http\Models\Role;
use App\Http\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Clerk\CommonController;
use Hash;

class UsersController extends CommonController
{
    // 重置密码
    public function resetPwd()
    {
        return view('admin.users.reset');
    }

    public function postReset(Request $request)
    {
        $user = User::find($request->user()->id);

        $validator = Validator::make($request->all(), [
            'old_password'          => 'required',
            'password'              => 'required|min:6|max:20|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.min' => '请输入不少于6位的密码',
            'password.max' => '请输入不多于20位的密码',
            'old_password.required' => '旧密码不能为空',
            'password_confirmation.required' => '确认密码不能为空',
            'password_confirmation.confirmed' => '两次密码不一致',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        // 校验密码
        $match = Hash::check($request->get('old_password'), $user->password);

        if (! $match) {
            return $this->error('原始密码错误');
        }

        $password = bcrypt($request->get('password'));

        if($user->update(['password' => $password])){
            return $this->success();
        } else {
            return $this->error();
        }
    }
}
