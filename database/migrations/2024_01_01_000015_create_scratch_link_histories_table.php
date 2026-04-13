<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scratch_link_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scratch_link_id')->nullable();
            $table->datetime('date')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('mac_address', 17)->nullable();
            $table->string('device', 191)->nullable();
            $table->string('os', 191)->nullable();
            $table->string('browser', 191)->nullable();
            $table->string('device_type', 191)->nullable();
            $table->string('country', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('region', 191)->nullable();
            $table->string('area_code', 191)->nullable();
            $table->string('country_code', 191)->nullable();
            $table->string('continent', 191)->nullable();
            $table->string('latitude', 191)->nullable();
            $table->string('logitude', 191)->nullable();
            $table->string('currency', 191)->nullable();
            $table->string('timezone', 191)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scratch_link_histories');
    }
};
