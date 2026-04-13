<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('razorpay_order_id', 100)->nullable();
            $table->string('razorpay_payment_id', 100)->nullable();
            $table->string('razorpay_signature', 255)->nullable();
            $table->integer('scratch_count')->default(0);
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('INR');
            $table->string('status', 20)->default('pending');
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('user_id', 'payment_history_user_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_history');
    }
};
