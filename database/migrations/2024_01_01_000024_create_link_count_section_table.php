<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_count_section', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('user_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->string('section_name', 100)->nullable();
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_count_section');
    }
};
