<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('MESDB')->create('employee_hist', function (Blueprint $table) {
            $table->id();
            $table->uuid('employee_uuid');
            $table->string('department_code', 25)->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('company_id');
            $table->string('empl_id', 20)->nullable();
            $table->date('join_date')->nullable();
            $table->date('release_date')->nullable();
            $table->timestamps();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::connection('MESDB')->table('employee_hist', function (Blueprint $table) {
            $table->foreign('employee_uuid')->references('uuid')->on('employees');
            $table->foreign('department_code')->references('code')->on('departments');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('MESDB')->dropIfExists('employee_hist');
    }
}
