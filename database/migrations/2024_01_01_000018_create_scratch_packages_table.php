<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scratch_packages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('scratch_count');
            $table->decimal('rate', 10, 2);
            $table->integer('total_amount');
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scratch_packages');
    }
};
