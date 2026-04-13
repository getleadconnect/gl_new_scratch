<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scratch_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->string('short_code', 191)->nullable();
            $table->string('link', 191)->nullable();
            $table->string('link_type', 50)->nullable();
            $table->string('qrcode_file', 100)->nullable();
            $table->text('url')->nullable();
            $table->boolean('bill_number_required')->default(0);
            $table->boolean('email_required')->nullable();
            $table->boolean('branch_required')->default(0);
            $table->integer('click_count')->default(0);
            $table->tinyInteger('type')->default(1);
            $table->boolean('status')->nullable();
            $table->bigInteger('link_count_section_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scratch_links');
    }
};
