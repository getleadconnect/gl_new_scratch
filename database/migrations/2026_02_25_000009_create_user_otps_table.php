<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_otps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 250)->nullable()->index();
            $table->string('number', 50)->nullable();
            $table->string('otp', 191);
            $table->enum('otp_type', ['signup', 'login', 'scratch_web', 'scratch_api'])->nullable();
            $table->dateTime('expiry')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
};
