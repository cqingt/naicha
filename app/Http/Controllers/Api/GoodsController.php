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

        // 定义温度
        $tempArr = [
            [ 'name' => '正常冰', 'flag' => 'normal'],
            [ 'name' => '少冰', 'flag' => 'small'],
            [ 'name' => '去冰', 'flag' => 'noIce'],
            [ 'name' => '温', 'flag' => 'warm'],
            [ 'name' => '热', 'flag' => '热']
        ];

        // 推荐配方
        $recommends = [
            [
                'id' => 1,
                'goods' => [
                    'base' => [
                        'name' => "回甘普洱",
                        'flag' => "base",
                        'volume' => 250,
                        'calorie' => 5,
                        'id' => 1,
                        'image' => "http://static.collocationlab.com/images/base/huiganpuer.png",
                        'price' => "12",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'taste' => [
                        'name' => "全脂奶",
                        'flag' => "taste",
                        'volume' => 50,
                        'calorie' => 9,
                        'id' => 12,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "3",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'flavor' => [
                        'name' => "芒果",
                        'flag' => "flavor",
                        'volume' => 150,
                        'calorie' => 8,
                        'id' => 21,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "5",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'burdening' => [
                        'name' => "燕麦粒",
                        'flag' => "burdening",
                        'volume' => 50,
                        'calorie' => 7,
                        'id' => 35,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "5",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'sugar' => [
                        'name' => "蔗糖(正常糖)",
                        'flag' => "sugar",
                        'volume' => 0,
                        'calorie' => 9,
                        'id' => 50,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "3",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'temperature' => [
                        'name' => "正常冰",
                        'flag' => "temperature",
                        'volume' => 0,
                        'calorie' => 9,
                        'id' => 52,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "0",
                        'reject_id' => "",
                        'deploy' => ""
                    ]
                ],
                'images' => [
                    'goods_text' => 'http://xiaohao.image.cidianpu.com/20190109102608.png',
                    'goods_image' => 'http://xiaohao.image.cidianpu.com/recommend03.png',
                    'active' => '',
                    'text' => ''
                ]
            ],

            [
                'id' => 2,
                'goods' => [
                    'base' => [
                        'name' => "正山小种",
                        'flag' => "base",
                        'volume' => 250,
                        'calorie' => 1,
                        'id' => 4,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "10",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'taste' => [
                        'name' => "可可汁",
                        'flag' => "taste",
                        'volume' => 50,
                        'calorie' => 6,
                        'id' => 14,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "5",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'flavor' => [
                        'name' => "椰果",
                        'flag' => "flavor",
                        'volume' => 150,
                        'calorie' => 6,
                        'id' => 36,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "3",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'burdening' => [
                        'name' => "燕麦粒",
                        'flag' => "burdening",
                        'volume' => 50,
                        'calorie' => 7,
                        'id' => 35,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "5",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'sugar' => [
                        'name' => "蔗糖(正常糖)",
                        'flag' => "sugar",
                        'volume' => 0,
                        'calorie' => 9,
                        'id' => 50,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "3",
                        'reject_id' => "",
                        'deploy' => ""
                    ],
                    'temperature' => [
                        'name' => "正常冰",
                        'flag' => "temperature",
                        'volume' => 0,
                        'calorie' => 9,
                        'id' => 52,
                        'image' => "http://xiaohao.image.cidianpu.com/nc_goods.png",
                        'price' => "0",
                        'reject_id' => "",
                        'deploy' => ""
                    ]
                ],
                'images' => [
                    'goods_text' => 'http://xiaohao.image.cidianpu.com/20190109102608.png',
                    'goods_image' => 'http://xiaohao.image.cidianpu.com/recommend03.png',
                    'active' => '',
                    'text' => ''
                ]
            ],
        ];

        return $this->_successful(['goods' => $goodsArr, 'tempArr' => $tempArr, 'recommends' => $recommends]);
    }
}