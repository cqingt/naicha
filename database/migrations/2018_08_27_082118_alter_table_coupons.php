<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCoupons extends Migration
{
    protected $tableName = 'coupons';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->decimal('match_price')->nullable()->comment('满足金额')->after('amount');
            $table->decimal('reduced_price')->nullable()->comment('减免金额')->after('match_price');
            $table->boolean('is_send')->default('0')->comment('是否发放')->after('reduced_price');
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
            $table->dropColumn('match_price');
            $table->dropColumn('reduced_price');
            $table->dropColumn('is_send');
        });
    }
}
