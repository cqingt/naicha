<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrders extends Migration
{
    protected $tableName = 'orders';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->comment('店铺ID')->index();
            $table->integer('member_id')->comment('会员ID')->index();
            $table->string('order_sn', 32)->comment('订单号')->index();
            $table->decimal('price')->default(0)->comment('订单实际价格');
            $table->decimal('original_price')->default(0)->comment('订单原价格');
            $table->decimal('reduced_price')->default(0)->comment('订单优惠价格');
            $table->boolean('pay_type')->default('0')->comment('支付方式：0线下，1微信');
            $table->boolean('status')->default('0')->comment('订单状态：0待支付，1已支付，2待领取，3已完成，4已退单，5异常');
            $table->timestamp('payed_at')->default('0')->comment('支付时间');
            $table->string('operator')->nullable()->comment('操作员');
            $table->integer('coupon_id')->default('0')->comment('优惠券ID')->index();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '订单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->tableName);
    }
}
