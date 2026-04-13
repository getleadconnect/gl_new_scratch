<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scratch_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('unique_id', 191)->nullable();
            $table->string('name', 191)->nullable();
            $table->integer('country_code')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->string('cust_mobile', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->bigInteger('campaign_id')->nullable();
            $table->integer('campaign_gift_id')->nullable();
            $table->text('offer_text')->nullable();
            $table->string('short_code', 50)->nullable();
            $table->string('bill_no', 191)->nullable();
            $table->tinyInteger('win_status')->nullable();
            $table->boolean('redeem')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->boolean('type_id')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->string('redeem_source', 100)->nullable();
            $table->datetime('redeemed_on')->nullable();
            $table->integer('redeemed_agent')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scratch_customers');
    }
};
