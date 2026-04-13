<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('settings_type', 100);
            $table->string('settings_value', 100);
            $table->integer('user_id')->nullable();
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->index('id', 'pk_int_settings_id');
            $table->index('user_id', 'fk_int_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
