<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WmsOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 191)->unique();
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('purchase_type_id');
            $table->string('customer_number');
            $table->string('document_number');
            $table->text('notes');
            $table->string('awb');
            $table->timestamp('estimation_send_date');
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();

            $table->timestamp('received_at')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('pick_up_id')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('status_id')->references('id')->on('mst_status');
            $table->foreign('purchase_type_id')->references('id')->on('purchase_types');
            $table->foreign('received_by')->references('id')->on('users');

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('order_delivery', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('type');
            $table->timestamp('do_date');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('order_receiver', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('name');
            $table->string('phone');
            $table->string('postal_code');
            $table->string('destination');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('sku');
            $table->integer('qty');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('order_pick_up', function (Blueprint $table) {
            $table->id();
            $table->string('pick_up_number', 191)->unique();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->string('name');
            $table->string('driver');
            $table->string('vehicle');
            $table->string('police_no');
            $table->timestamp('picked_at')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('delivery_id')->references('id')->on('order_delivery');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('delivery_id')->references('id')->on('order_delivery');
            $table->foreign('receiver_id')->references('id')->on('order_receiver');
            $table->foreign('pick_up_id')->references('id')->on('order_pick_up');
        });

        Schema::create('orbit_stock_upload_history', function (Blueprint $table) {
            $table->id();
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
        });

        Schema::create('orbit_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('router_id');
            $table->unsignedBigInteger('simcard_id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('purchase_type_id');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->unsignedBigInteger('upload_id')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('router_id')->references('id')->on('routers');
            $table->foreign('simcard_id')->references('id')->on('simcards');
            $table->foreign('status_id')->references('id')->on('mst_status');
            $table->foreign('purchase_type_id')->references('id')->on('purchase_types');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('order_item_id')->references('id')->on('order_items');
            $table->foreign('upload_id')->references('id')->on('orbit_stock_upload_history');
        });

        Schema::create('order_item_orbits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('orbit_stock_id');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('order_item_id')->references('id')->on('order_items');
            $table->foreign('orbit_stock_id')->references('id')->on('orbit_stocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_orbits');
        Schema::dropIfExists('orbit_stocks');
        Schema::dropIfExists('order_pick_up');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_delivery');
        Schema::dropIfExists('orders');
    }
}
