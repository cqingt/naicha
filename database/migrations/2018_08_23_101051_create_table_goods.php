<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGoods extends Migration
{
    protected $tableName = 'goods';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->default(0)->comment('分类ID')->index();
            $table->string('name', 100)->comment('商品名称')->index();
            $table->string('image', 255)->comment('商品图片地址');
            $table->integer('stock')->default(0)->comment('库存');
            $table->decimal('price')->default(0)->comment('价格');
            $table->string('deploy')->default('')->comment('商品配置：少冰#多冰  多值#号隔开');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '商品表'");
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
