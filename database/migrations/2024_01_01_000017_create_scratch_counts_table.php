<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scratch_counts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('total_count')->nullable();
            $table->integer('used_count')->nullable();
            $table->integer('balance_count')->nullable();
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();

            $table->index('id', 'id');
            $table->index('user_id', 'fk_int_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scratch_counts');
    }
};
