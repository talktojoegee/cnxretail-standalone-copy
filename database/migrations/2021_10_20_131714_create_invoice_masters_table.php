<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->integer('invoice_no');
            $table->string('ref_no')->nullable();
            $table->dateTime('issue_date');
            $table->dateTime('due_date')->nullable();
            $table->double('total')->default(0);
            $table->double('sub_total')->default(0);
            $table->double('vat_rate')->nullable()->default(0);
            $table->double('vat_amount')->default(0)->nullable();
            $table->string('slug')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=paid,2=partly-paid'); //pending
            $table->double('paid_amount')->default(0);
            $table->integer('currency_id')->default(1)->nullable();
            $table->double('exchange_rate')->default(1)->nullable();
            #Posting
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->tinyInteger('posted')->default(0)->comment('0=not posted, 1=posted');
            $table->dateTime('post_date')->nullable();
            $table->tinyInteger('trash')->default(0)->comment('0=not, 1=trashed');
            $table->unsignedBigInteger('trashed_by')->nullable();
            $table->dateTime('trash_date')->nullable();
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
        Schema::dropIfExists('invoice_masters');
    }
}
