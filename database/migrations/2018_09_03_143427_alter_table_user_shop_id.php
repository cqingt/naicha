<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserShopId extends Migration
{
    protected $tableName = 'users';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->integer('shop_id')->default('0')->comment('店铺ID')->after('id');
            $table->string('real_name')->default('')->comment('真实姓名')->after('name');
            $table->string('telephone')->default('')->comment('手机号')->after('real_name');
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
            $table->dropColumn('real_name');
            $table->dropColumn('telephone');
        });
    }
}
