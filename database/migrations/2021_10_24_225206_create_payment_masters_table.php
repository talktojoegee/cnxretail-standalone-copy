<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('bill_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->dateTime('payment_date');
            $table->unsignedBigInteger('payment_no')->nullable();
            $table->tinyInteger('payment_type')->default(1)->comment('1=through invoice;2=direct');
            //$table->dateTime('issue_date');
            $table->string('ref_no');
            $table->double('amount');
            $table->double('exchange_rate')->default(1)->nullable();
            $table->unsignedBigInteger('currency_id')->default(1)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=paid,2=partly-paid'); //pending

            $table->string('slug')->nullable();
            $table->string('memo')->nullable();
            $table->tinyInteger('posted')->default(0);
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->dateTime('date_posted')->nullable();
            $table->tinyInteger('trash')->default(0);
            $table->unsignedBigInteger('trashed_by')->nullable();
            $table->dateTime('date_trashed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_masters');
    }
}
