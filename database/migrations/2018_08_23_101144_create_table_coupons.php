<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCoupons extends Migration
{
    protected $tableName = 'coupons';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->comment('店铺ID');
            $table->string('title')->comment('标题');
            $table->string('image')->nullable()->comment('图片');
            $table->string('condition')->nullable()->comment('使用条件');
            $table->integer('amount')->default('0')->comment('发放数量');
            $table->timestamp('start_time')->default('0')->comment('有效开始时间');
            $table->timestamp('stop_time')->default('0')->comment('有效截止时间');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '优惠券表'");
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
