<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableShops extends Migration
{
    protected $tableName = 'shops';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('flag')->comment('店铺标识')->unique();
            $table->string('name')->comment('店铺名称');
            $table->string('address')->nullable()->comment('地址');
            $table->string('contact')->nullable()->comment('联系方式');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '店铺表'");
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
