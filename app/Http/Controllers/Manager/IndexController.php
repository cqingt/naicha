<?php

namespace App\Http\Controllers\Manager;

use App\Http\Models\Category;
use App\Http\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return view('manager.layouts.index', compact('categories'));
    }

    /**
     * 记录列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function records(Request $request)
    {
        $perPage = $request->get('limit'); // 每页数量由首页控制
        $categoryId = $request->get('category_id');
        $name = $request->get('name');

        $query = Goods::withTrashed();

        if ($categoryId) {
            $query->where('category_id', '=', $categoryId);
        }

        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }

        $data = $query->orderBy('id', 'desc')->paginate($perPage);
        $items = $data->items();

        foreach ($items as &$item) {
            $item['category_name'] = $item->category->name;
            $item['status'] = $item->deleted_at ? '已下架' : '正常';
        }

        $result = [
            'code'  =>  0,
            'msg'   =>  '',
            'count' => $data->total(),
            'data'  => $items,
        ];
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 上下架
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $action = $request->get('action');

        if ($action == 'online') {
            $result = Goods::withTrashed()->find($id)->restore();
        } else {
            $result = Goods::find($id)->delete();
        }

        if ($result) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
