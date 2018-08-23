<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePushes extends Migration
{
    protected $tableName = 'pushes';
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
            $table->string('title')->nullable()->comment('标题');
            $table->string('image')->nullable()->comment('图片');
            $table->string('url')->nullable()->comment('链接地址');
            $table->string('content')->nullable()->comment('内容');
            $table->integer('position')->default(0)->comment('排序');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '推送表'");
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
