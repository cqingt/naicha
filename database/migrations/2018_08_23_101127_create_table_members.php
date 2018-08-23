<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMembers extends Migration
{
    protected $tableName = 'members';

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
            $table->string('username')->comment('用户名称');
            $table->string('telephone', 11)->nullable()->comment('手机号');
            $table->string('avatar')->default('')->comment('头像');
            $table->integer('age')->default('0')->comment('年龄');
            $table->boolean('gender')->default('0')->comment('性别');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '会员表'");
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
