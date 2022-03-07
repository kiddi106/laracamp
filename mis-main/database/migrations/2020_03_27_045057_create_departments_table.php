<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->string('code', 5)->primary();
            $table->string('parent_code', 5)->nullable();
            $table->string('name')->unique();
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('parent_code')->references('code')->on('departments');
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
        Schema::dropIfExists('departments');
    }
}
