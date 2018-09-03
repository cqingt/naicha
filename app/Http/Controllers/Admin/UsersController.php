<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Http\Models\User;
use App\Http\Models\Role;
use App\Http\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CommonController;
use Hash;

class UsersController extends CommonController
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function show(Request $request)
    {
        $perPage = $request->get('limit'); // 每页数量由首页控制

        $data = User::orderBy('id', 'asc')->paginate($perPage);

        $items = $data->items();

        foreach ($items as &$item) {
            $item['roles'] = $item->role ? $item->role->name : '--';
            $item['shop_name'] = $item->shop ? $item->shop->name : '--';
        }
        return [
            'code'  =>  0,
            'msg'   =>  '',
            'count' => $data->total(),
            'data'  => $items,
        ];
    }

    public function create(User $user)
    {
        $roles = Role::all();
        $shops = Shop::all();
        return view('admin.users.create_and_edit',compact('user','roles', 'shops'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|min:3|max:15',
            'email'     =>  'email|unique:users,email',
            'password'  =>  'required|min:6|max:20|confirmed',
            'role_id'   => 'required',
            'real_name' => 'required',
            'telephone' => 'regex:/^1[34578][0-9]{9}$/',
            'shop_id'   => 'integer',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $result = User::create([
            'name'  =>  $request->name,
            'email' =>  $request->email ? : $request->name . '@email.com',
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ]);

        if ($result) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $shops = Shop::all();

        return view('admin.users.create_and_edit',compact('user','roles', 'shops'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $data = array_filter($request->all());

        $validator = Validator::make($request->all(), [
            'name'      => 'required|min:3|max:15',
            'real_name' => 'required',
            'telephone' => 'regex:/^1[34578][0-9]{9}$/',
            'shop_id'   => 'integer',
            'email'     =>  'email|unique:users,email,'.$user->id,
            'password'  =>  'required|min:6|confirmed',
            'role_id'   => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $data['email'] = ! isset($data['email']) ? $data['name'] . '@email.com' : $data['email'];

        if ($user->update($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function destroy($id)
    {
        User::find($id)->delete();

        return ['code'=>1,'msg'=>'删除成功'];
    }

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
