<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumn2ToReceiptMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_masters', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=paid,2=partly-paid'); //pending
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->unsignedBigInteger('trashed_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipt_masters', function (Blueprint $table) {
            //
        });
    }
}
