<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Models\Role;
use Validator;

class RolesController extends CommonController
{
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = new Role();
        return view('admin.roles.create_and_edit',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|min:2|unique:roles,name',
        ], [
            'name.min' => '角色名最少2个字符',
            'name.required' => '角色名必填',
            'name.unique' => '角色名已存在',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $result = Role::create([
            'name'        =>  $request->name,
            'description' => $request->description
        ]);

        if($result){
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 列表API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function records(Request $request)
    {
        $perPage = $request->get('limit'); // 每页数量由首页控制

        $data = Role::orderBy('id', 'asc')->paginate($perPage);

        $items = $data->items();

        $result = [
            'code'  =>  0,
            'msg'   =>  '',
            'count' => $data->total(),
            'data'  => $items
        ];
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::find($id);

        return view('admin.roles.create_and_edit', compact('roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|min:2|unique:roles,name,' . $id,
        ], [
            'name.min' => '角色名最少2个字符',
            'name.required' => '角色名必填',
            'name.unique' => '角色名已存在',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $result = Role::find($id)->update([
            'name'        =>  $request->name,
            'description' => $request->description
        ]);

        if($result){
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * 删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (Role::find($id)->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}
