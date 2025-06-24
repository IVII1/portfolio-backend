<?php

use Carbon\Carbon;
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
        Schema::create('calendar_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date_published')->unique()->default(Carbon::today());
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->boolean('highlighted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_entries');
    }
};
