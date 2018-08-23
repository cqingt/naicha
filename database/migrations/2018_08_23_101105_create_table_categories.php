<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTableCategories extends Migration
{
    protected $tableName = 'categories';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->comment('商品名称')->index();
            $table->integer('position')->default(0)->comment('排序');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '分类表'");
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
