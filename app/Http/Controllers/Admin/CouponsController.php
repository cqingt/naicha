<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\MemberCoupon;
use App\Http\Models\Member;
use Illuminate\Http\Request;
use App\Http\Models\Shop;
use App\Http\Models\Coupon;
use App\Http\Requests;
use Validator;
use App\Http\Controllers\Controller;


class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.coupons.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::all();
        return view('admin.coupons.create', compact('shops'));
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
            'title'      => 'required',
            'start_time' => 'required',
            'stop_time'  => 'required',
            'shop_id'    => 'integer',
            'match_price'   => 'required',
            'reduced_price' => 'required',
            'amount'        => 'integer'
        ], [
            'title.required' => '优惠券标题不能为空',
            'start_time.required' => '有效期开始时间不能为空',
            'stop_time.required' => '有效期结束时间不能为空',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $condition = ['MATCH' => $request->match, 'PRICE' => $request->price];

        $result = Coupon::create([
            'title'      =>  $request->title,
            'start_time' => $request->start_time,
            'stop_time'  =>  $request->stop_time,
            'shop_id'    => $request->shop_id,
            'amount'     => $request->amount,
            'match_price'   => $request->match_price,
            'reduced_price' => $request->reduced_price
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
    public function list(Request $request)
    {
        $perPage = $request->get('limit'); // 每页数量由首页控制

        $data = Coupon::orderBy('id', 'desc')->paginate($perPage);

        $items = $data->items();

        foreach ($items as &$item) {
            $item['shop_name'] = $item->shop ? $item->shop->name : '--';
            $item['send_status'] = $item->is_send ? '已发放' : '未发放';
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
        $coupon = Coupon::find($id);
        $shops = Shop::all();

        if ($coupon->is_send) {
            $this->error('优惠券已经发放，不能编辑');
        }

        return view('admin.coupons.edit', compact('coupon', 'shops'));
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
        $coupon = Coupon::find($id);

        if ($coupon->is_send) {
            $this->error('优惠券已经发放，不能编辑');
        }

        $validator = Validator::make($request->all(), [
            'title'         => 'required',
            'start_time'    => 'required',
            'stop_time'     => 'required',
            'shop_id'       => 'integer',
            'match_price'   => 'required',
            'reduced_price' => 'required',
            'amount'        => 'integer'
        ], [
            'title.required' => '优惠券标题不能为空',
            'start_time.required' => '有效期开始时间不能为空',
            'stop_time.required' => '有效期结束时间不能为空',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return $this->error($error);
        }

        $result = Coupon::find($id)->update([
            'title'      =>  $request->title,
            'start_time' => $request->start_time,
            'stop_time'  =>  $request->stop_time,
            'shop_id'    => $request->shop_id,
            'amount'     => $request->amount,
            'match_price'   => $request->match_price,
            'reduced_price' => $request->reduced_price
        ]);

        if($result){
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
        if (Coupon::find($id)->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    // 优惠券发放
    public function grant(Request $request, $id)
    {
        $coupon = Coupon::find($id);

        if ($coupon->is_send) {
            $this->error('优惠券已发放');
        }

        $members = Member::orderBy('id', 'desc')->get();

        $time = 0;
        foreach ($members as $member) {
            if ($coupon->amount > 0) {
                if ($time == $coupon->amount) {
                    break;
                } else {
                    MemberCoupon::create([
                        'coupon_id' => $coupon->id,
                        'member_id' => $member->id,
                    ]);
                }
            }

            $time ++;
        }

        $coupon->update(['is_send' => 1]);

        // 更新状态，用户表新增一个优惠券记录

        return $this->success();
    }
}
