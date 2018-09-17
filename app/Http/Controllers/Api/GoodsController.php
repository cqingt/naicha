<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Models\Category;
use App\Http\Models\Goods;

class GoodsController extends CommonController
{
    // 商品
    public function index(Request $request)
    {
        $categories = Category::select(['id', 'volume', 'name'])->get();
        $newCategory = [];
        foreach ($categories as $category) {
            $newCategory[$category->id] = $category;
        }

        $products = Goods::withTrashed()->orderBy('category_id')->get();

        $data = [];

        foreach ($products as $product) {
            $data[$product['category_id']]['volume'] = $newCategory[$product['category_id']]['volume'];
            $data[$product['category_id']]['items'][] = $product;
        }

        // 2级品类分组 id
        $categoryOne = [20, 21, 22, 23, 24, 25];
        $categoryTwo = [26, 27, 28, 29];
        $categoryThree = [30, 31, 32, 33];

        $categoryMilk = [7, 8, 9, 16, 17]; // 排斥柑橘类

        return $this->_successful(
            [
                'goods'   => $data,
                'products' => $products,
                'categoryOne' => $categoryOne,
                'categoryTwo' => $categoryTwo,
                'categoryThree' => $categoryThree,
                'categoryMilk' => $categoryMilk
            ]
        );
    }

}