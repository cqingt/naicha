<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Models\Category;
use App\Http\Models\Goods;
use App\Http\Models\Formula;
use App\Http\Models\Order;
use DB;

class GoodsController extends CommonController
{
    // 商品
    public function index(Request $request)
    {
        $categories = Category::select(['id', 'volume', 'name', 'flag'])->get();
        $newCategory = [];
        foreach ($categories as $category) {
            $newCategory[$category->id] = $category;
        }

        $products = Goods::withTrashed()->orderBy('category_id')->get();

        $data = [];

        foreach ($products as $product) {
            $product['soldOut'] = $product['deleted_at'] ? true : false ;
            $data[$product['category_id']]['volume'] = $newCategory[$product['category_id']]['volume'];
            $data[$product['category_id']]['items'][] = $product;
        }

        // 2级品类分组 id
        $citrusCategory = [26, 27, 28, 29]; // 柑橘类

        $milkCategory = [7, 8, 9, 14, 15, 16, 17]; // 排斥柑橘类

        return $this->_successful(
            [
                'products'   => $data,
                'citrus' => $citrusCategory,
                'milk' => $milkCategory,
                'defaultItem' => [
                    'name' => '滑动选择',
                    'price' => 0,
                    'image' => 'http://cqingt.oss-cn-shenzhen.aliyuncs.com/image/timg3.jpg',
                    'volume' => 0,
                    'calorie' => 0,
                    'pk' => 999,
                    'show' => true
                ],
                'doubleItem' => [
                    'name' => '一级品类双倍',
                    'price' => '0.00',
                    'image' => 'https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png',
                    'volume' => 250,
                    'calorie' => 0,
                    'pk' => 999,
                    'show' => true
                ],
            ]
        );
    }

    // 新的产品
    public function items(Request $request)
    {
        $goodses = DB::table('goods')
            ->join('categories', 'goods.category_id', '=', 'categories.id')
            ->select(['categories.name','categories.flag','categories.volume','goods.id','goods.name','goods.image','goods.price','goods.reject_id','goods.deploy', 'goods.calorie'])
            ->where('goods.online', 1)
            ->orderBy('goods.id', 'asc')
            ->get();

        $goodsArr = [];

        foreach ($goodses as $goods) {
            $goodsArr[$goods->flag][] = (array)$goods;
        }

        return $this->_successful(['goods' => $goodsArr]);
        //echo '<pre>';
        //print_r($goodsArr);
    }
}