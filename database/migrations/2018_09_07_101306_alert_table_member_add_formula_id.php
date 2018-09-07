<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTableMemberAddFormulaId extends Migration
{
    protected $tableName = 'members';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->string('openid')->default('')->comment('微信openID')->after('gender');
            $table->integer('formula_id')->default('0')->comment('用户首推配方')->after('gender');
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
            $table->dropColumn('formula_id');
            $table->dropColumn('openid');
        });
    }
}
