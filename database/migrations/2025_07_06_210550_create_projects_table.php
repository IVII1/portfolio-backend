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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('owner');
            $table->string('repo');
            $table->string('title');
            $table->string('slug');
            $table->text('subtitle');
            $table->text('description');
            $table->string('github_url');
            $table->string('live_url')->nullable();
            $table->string('purpose');
            $table->enum('type',['Solo Project', 'Collaboration', 'Open Source Contribution'])->default('Solo Project');
            $table->timestamp('date_started');
            $table->integer('commit_count');
            $table->string('language');
            $table->json('challenges');
            $table->json('features');
            $table->json('stack');
            $table->json('key_takeaways');
            $table->json('gallery')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
