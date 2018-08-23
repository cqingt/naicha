<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Shop;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShopsController extends Controller
{
    /**
     * 列表页
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.shops.index');
    }

    /**
     * 创建
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shops.create');
    }

    /**
     * 保存新增
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'  => 'required|min:2|max:25|unique:shops,name',
            'address' =>  'required',
            'contact'  =>  'required'
        ]);

        $maxId = Shop::max('id');
        $maxId = $maxId ? 10001 + $maxId : 10001;

        $user = Shop::create([
            'name'     =>  $request->name,
            'flag'     => 'Shop' . $maxId,
            'address'  =>  $request->address,
            'contact'  =>  $request->contact,
        ]);

        if($user){
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

    }

    /**
     * 列表API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $perPage = $request->get('limit'); // 每页数量由首页控制

        $data = Shop::orderBy('id', 'desc')->paginate($perPage);

        $result = [
            'code'  =>  0,
            'msg'   =>  '',
            'count' => $data->total(),
            'data'  => $data->items(),
        ];
        return response()->json($result);
    }

    /**
     * 编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop = Shop::find($id);

        return view('admin.shops.edit', compact('shop', $shop));
    }

    /**
     * 更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shop = Shop::find($id);
        $data = $request->all();

        $this->validate($request,[
            'address'  => 'required',
            'contact' =>  'required'
        ]);

        if ($shop->update($data)) {
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
        if (Shop::find($id)->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}
