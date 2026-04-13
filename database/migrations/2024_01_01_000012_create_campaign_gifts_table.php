<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_gifts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->integer('gift_count');
            $table->text('description');
            $table->string('gift_image', 191)->nullable();
            $table->integer('balance_count')->nullable();
            $table->integer('user_id');
            $table->integer('type_id')->nullable();
            $table->integer('winning_status');
            $table->integer('status');
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_gifts');
    }
};
