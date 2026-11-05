<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id')->default(1)->nullable();
            $table->string('website')->nullable();
            $table->string('company_name');
            $table->string('email');
            $table->string('phone_no')->nullable();
            $table->string('logo')->default('logo.png')->nullable();
            $table->string('favicon')->default('favicon.png')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->tinyInteger('account_status')->default(1)->comment('1=active,0=expired'); //0=expired; 1=active
            $table->string('email_signature')->nullable();
            $table->string('email_signature_image')->nullable();
            $table->string('address')->nullable();
            $table->string('active_sub_key')->nullable();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->text('slug')->nullable();
            $table->text('invoice_terms')->nullable();
            $table->text('receipt_terms')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('public_key')->nullable();
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
        Schema::dropIfExists('tenants');
    }
}
