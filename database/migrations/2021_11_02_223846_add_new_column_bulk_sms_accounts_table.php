<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnBulkSmsAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bulk_sms_accounts', function (Blueprint $table) {
            $table->double('unit_debit')->default(0)->comment('unit debit side');
            $table->double('unit_credit')->default(0)->comment('unit credit side');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bulk_sms_accounts', function (Blueprint $table) {
            //
        });
    }
}
