<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('parent_uuid')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('empl_id', 20)->nullable();
            $table->date('join_date')->nullable();
            $table->string('ext_no', 5)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->string('pob', 20)->nullable();
            $table->date('dob')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('parent_uuid')->references('uuid')->on('employees');
            $table->foreign('created_by')->references('uuid')->on('employees');
            $table->foreign('updated_by')->references('uuid')->on('employees');
            $table->foreign('deleted_by')->references('uuid')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
