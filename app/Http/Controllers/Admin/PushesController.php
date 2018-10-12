<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Push;
use App\Http\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CommonController;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Illuminate\Http\Exception\HttpResponseException;

class PushesController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pushes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::all();
        return view('admin.pushes.create', compact('shops'));
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
            'title'   => 'required|min:2|max:25',
            'image'   =>  'required'
        ], [
            'title.min' => '标题最少2个字符',
            'title.max' => '标题不能多于25个字符',
            'image.required' => '图片必填',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $result = Push::create([
            'title'   =>  $request->title,
            'shop_id' => $request->shop_id,
            'image'   => $request->root() . $request->image,
            'position' => $request->position
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

        $data = Push::orderBy('id', 'desc')->paginate($perPage);

        $items = $data->items();

        foreach ($items as &$item) {
            $item['shop_name'] = $item->shop ? $item->shop->name : '--';
        }

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
        $shops = Shop::all();
        $push = Push::find($id);

        return view('admin.pushes.edit', compact('shops', 'push'));
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
            'title'   => 'required|min:2|max:25',
            'image'   =>  'required'
        ], [
            'title.min' => '标题最少2个字符',
            'title.max' => '标题不能多于25个字符',
            'image.required' => '图片必填',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $result = Push::find($id)->update([
            'title'   =>  $request->title,
            'shop_id' => $request->shop_id,
            'image'   => $request->root() . $request->image,
            'position' => $request->position
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
        if (Push::find($id)->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}
