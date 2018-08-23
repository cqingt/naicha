<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderDetails extends Migration
{
    protected $tableName = 'order_details';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('goods_name', 255)->comment('商品名称');
            $table->integer('order_id')->comment('订单ID');
            $table->integer('goods_num')->comment('商品数量');
            $table->decimal('goods_price')->comment('商品价格');
            $table->integer('package_num')->default('1')->comment('口袋数');
            $table->string('deploy')->nullable()->comment('商品配置');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '订单详情表'");
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
