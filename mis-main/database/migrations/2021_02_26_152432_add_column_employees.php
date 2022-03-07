<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('MESDB')->table('employees', function (Blueprint $table) {
            $table->string('bank_account', 40)->after('dob')->nullable();
            $table->string('npwp', 40)->after('dob')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('MESDB')->table('employees', function (Blueprint $table) {
            $table->dropColumn('bank_account');
            $table->dropColumn('npwp');
        });
    }
}
