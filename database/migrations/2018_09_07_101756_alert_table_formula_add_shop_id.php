<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTableFormulaAddShopId extends Migration
{
    protected $tableName = 'formulas';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->integer('shop_id')->default('0')->comment('店铺ID')->after('member_id');
            $table->integer('order_id')->default('0')->comment('订单ID')->after('shop_id');
            $table->boolean('package_num')->default('1')->comment('订单中的第几杯')->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('shop_id');
            $table->dropColumn('order_id');
            $table->dropColumn('package_num');
        });
    }
}
