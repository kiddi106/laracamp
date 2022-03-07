<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->uuid('employee_uuid');
            $table->date('date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('employee_uuid')->references('uuid')->on('employees');
            $table->foreign('created_by')->references('uuid')->on('employees');
            $table->foreign('updated_by')->references('uuid')->on('employees');
            $table->foreign('deleted_by')->references('uuid')->on('employees');
        });
        
        Schema::create('attendance_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id');
            $table->text('note');
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::table('attendance_tasks', function (Blueprint $table) {
            $table->foreign('attendance_id')->references('id')->on('attendances');
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
        Schema::dropIfExists('attendance_tasks');
        Schema::dropIfExists('attendances');
    }
}
