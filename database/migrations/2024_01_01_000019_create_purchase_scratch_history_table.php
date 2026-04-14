<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_scratch_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('narration', 500);
            $table->integer('scratch_count');
            $table->integer('amount')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();

            $table->index('id', 'id');
            $table->index('user_id', 'user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_scratch_history');
    }
};
