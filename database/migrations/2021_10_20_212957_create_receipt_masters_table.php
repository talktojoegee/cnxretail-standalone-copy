<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->tinyInteger('receipt_type')->default(1)->comment('1=through invoice;2=direct');
            $table->string('ref_no');
            $table->dateTime('issue_date');
            $table->double('amount');
            $table->double('exchange_rate')->default(1)->nullable();
            $table->unsignedBigInteger('currency_id')->default(1)->nullable();
            $table->tinyInteger('payment_type')->default(1);
            $table->string('slug')->nullable();
            $table->string('memo')->nullable();
            $table->tinyInteger('posted')->default(0);
            $table->dateTime('date_posted')->nullable();
            $table->tinyInteger('trash')->default(0);
            $table->dateTime('date_trashed')->nullable();
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
        Schema::dropIfExists('receipt_masters');
    }
}
