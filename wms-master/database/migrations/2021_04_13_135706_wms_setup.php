<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WmsSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_status', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 191)->unique();
            $table->string('bgcolor', 20);

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('purchase_types', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 191)->unique();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('material_type', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 191)->unique();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('material', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 191)->unique();
            $table->unsignedInteger('type_id');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('type_id')->references('id')->on('material_type');
        });

        Schema::create('po', function (Blueprint $table) {
            $table->id();
            $table->string('po_no', 191)->unique();
            $table->date('po_at');
            $table->string('delivery_no');
            $table->date('receive_at');
            $table->text('description');
            $table->string('currency', 20)->nullable();
            $table->double('kurs', 16, 2);

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('po_dtl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id');
            $table->unsignedInteger('material_id')->nullable();
            $table->integer('qty');
            $table->string('uom', 10);
            $table->double('price', 16, 0);
            $table->double('total', 16, 0);
            $table->text('description');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('po_id')->references('id')->on('po');
            $table->foreign('material_id')->references('id')->on('material');
        });

        Schema::create('po_dtl_upload_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_dtl_id');
            $table->string('filename');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('po_dtl_id')->references('id')->on('po_dtl');
        });

        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_dtl_id')->nullable();
            $table->unsignedInteger('status_id');
            $table->enum('condition', ['GOOD', 'BAD']);
            $table->string('esn');
            $table->string('ssid');
            $table->string('password_router');
            $table->string('guest_ssid');
            $table->string('password_guest');
            $table->string('password_admin');
            $table->string('imei');
            $table->string('device_model');
            $table->string('device_type')->nullable();
            $table->string('color')->nullable();
            $table->string('location')->nullable();
            $table->string('notes')->nullable();
            $table->unsignedInteger('purchase_type_id')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('po_dtl_id')->references('id')->on('po_dtl');
            $table->foreign('status_id')->references('id')->on('mst_status');
            $table->foreign('purchase_type_id')->references('id')->on('purchase_types');
        });

        Schema::create('simcards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_dtl_id')->nullable();
            $table->unsignedInteger('status_id');
            $table->enum('condition', ['GOOD', 'BAD']);
            $table->string('serial_no');
            $table->string('msisdn');
            $table->string('item_code');
            $table->date('exp_at');
            $table->string('location')->nullable();
            $table->string('notes')->nullable();
            $table->unsignedInteger('purchase_type_id')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('po_dtl_id')->references('id')->on('po_dtl');
            $table->foreign('status_id')->references('id')->on('mst_status');
            $table->foreign('purchase_type_id')->references('id')->on('purchase_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simcards');
        Schema::dropIfExists('routers');
        Schema::dropIfExists('po_dtl_upload_history');
        Schema::dropIfExists('po_dtl');
        Schema::dropIfExists('po');
        Schema::dropIfExists('material_type');
        Schema::dropIfExists('material');
    }
}
