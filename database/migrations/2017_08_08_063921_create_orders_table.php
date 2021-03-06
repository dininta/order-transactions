<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->tinyInteger('status');
            $table->string('reason')->nullable();
            $table->string('payment_proof', 500)->nullable();
            $table->integer('coupon_id')->unsigned()->nullable();
            $table->integer('total_price')->unsigned();
            $table->string('name', 50);
            $table->string('email', 50);
            $table->string('phone_number', 50);
            $table->string('address');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('coupon_id')
                  ->references('id')
                  ->on('coupons')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
