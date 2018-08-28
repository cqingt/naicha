<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Http\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Hash;
use DB;

class UsersController extends Controller
{

    public function index()
    {
        return view('admin.users.index');
    }

    public function show(User $users, Request $request)
    {
        $count = $users->count();
        $data = getRoleOrPermissionApi($request,$users);

        //$data=$user->skip($limit*($page-1))->take($limit)->get()->toArray();

        return [
            'code'  =>  0,
            'msg'   =>  '',
            'count' =>  $count,
            'data'  => $data,
        ];
    }

    public function create(User $user)
    {
        $roles = Role::all()->pluck('name')->toArray();
        return view('admin.users.create_and_edit',compact('user','roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|min:3|max:15',
            'email' =>  'required|email|unique:users,email',
            'password'  =>  'required|min:6|max:20|confirmed'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $user=User::create([
            'name'  =>  $request->name,
            'email' =>  $request->email,
            'password' => bcrypt($request->password),
        ]);
        if($request->role){
            $user->assignRole($request->role);
        }
        return ['code'=>1,'msg'=>'添加成功'];
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        $roles = Role::all()->pluck('name')->toArray();
        $role = DB::table('user_has_roles')
            ->join('roles', 'user_has_roles.role_id', '=', 'roles.id')
            ->where('user_has_roles.user_id', '=', $user->id)
            ->pluck('name');
        //$role  = $user->roles()->pluck('name')->first();

        return view('admin.users.create_and_edit',compact('user','roles','role'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $data=array_filter($request->all());

        $validator = Validator::make($request->all(), [
            'name'  => 'required|min:3|max:15',
            'email' =>  'required|email|unique:users,email,'.$user->id,
            'password'  =>  'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $user->update($data);

        $roleId = DB::table('roles')->where('name', $request->get('role'))->pluck('id');

        $hasRole = DB::table('user_has_roles')->where('user_id', '=', $user->id)->get();

        if ($hasRole) {
            $hasRole->update(['role_id' => $roleId]);
        } else {
            DB::table('user_has_roles')->insert(['role_id' => $roleId, 'user_id' => $user->id]);
        }

        /*$role=$user->roles()->pluck('name')->first();
        if($role){
            $user->removeRole($role);
        }
        if($request->role){
            $user->assignRole($request->role);
        }*/
        return ['code'=>1, 'msg'=>'修改成功'];
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
