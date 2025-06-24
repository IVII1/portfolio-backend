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
        Schema::create('calendar_entry_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('reference_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['calendar_entry_id', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_entry_references');
    }
};
