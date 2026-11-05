<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('tenant_id');
            $table->string('description')->nullable();
            $table->double('exchange_rate')->default(1);
            $table->unsignedBigInteger('currency_id')->default(1);
            $table->unsignedBigInteger('service_id')->nullable()->comment('for direct receipt');
            $table->double('quantity')->default(0)->comment('# of items');
            $table->double('unit_cost')->default(0)->comment('cost/item');
            $table->double('payment')->default(0)->comment('total amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipt_details');
    }
}
