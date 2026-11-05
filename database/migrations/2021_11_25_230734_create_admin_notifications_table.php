<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->string('route_name')->nullable();
            $table->string('route_param')->nullable();
            $table->tinyInteger('route_type')->default(0)->comment('0=No param,1=yes param');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->tinyInteger('is_read')->nullable()->default(0)->comment('0=not read;1=read');
            $table->dateTime('read_at')->nullable();
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
        Schema::dropIfExists('admin_notifications');
    }
}
