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
            $table->string('user_id', 250)->nullable();
            $table->string('number', 50)->nullable();
            $table->string('otp', 191);
            $table->enum('otp_type', ['signup', 'login', 'scratch_web', 'scratch_api'])->nullable();
            $table->datetime('expiry')->nullable();
            $table->timestamps();

            $table->index('user_id', 'user_id');
            $table->index('id', 'id');
            $table->index('user_id', 'user_id_2');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
};
