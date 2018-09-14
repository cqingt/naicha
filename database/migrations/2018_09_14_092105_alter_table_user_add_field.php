<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserAddField extends Migration
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
            $table->string('country')->nullable()->comment('国家')->after('openid');
            $table->string('province')->nullable()->comment('省')->after('country');
            $table->string('city')->nullable()->comment('市')->after('province');
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
            $table->dropColumn('country');
            $table->dropColumn('province');
            $table->dropColumn('city');
        });
    }
}
