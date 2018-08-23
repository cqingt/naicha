<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFormulas extends Migration
{
    protected $tableName = 'formulas';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->comment('用户ID');
            $table->string('title')->comment('配方名称');
            $table->integer('likes')->default('0')->comment('点赞数');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '配方表'");
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
