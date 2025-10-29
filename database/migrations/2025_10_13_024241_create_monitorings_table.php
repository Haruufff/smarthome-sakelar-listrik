<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->decimal('voltage', 8, 2);
            $table->decimal('current', 8, 2);
            $table->decimal('energy', 10, 2);
            $table->decimal('power', 10, 2);
            $table->decimal('frequency', 5, 2)->nullable();
            $table->foreignId('tax_id')->constrained('taxes');
            $table->integer('total_price');
            $table->datetime('datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
};