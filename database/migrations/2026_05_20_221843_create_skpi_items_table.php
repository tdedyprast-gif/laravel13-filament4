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
        Schema::create('skpi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skpi_submission_id')->constrained()->cascadeOnDelete();
            $table->enum('category', [
                'achievement',
                'certificate',
                'organization',
                'internship',
                'language',
                'skill',
                'activity',
            ]);
            $table->string('title');
            $table->string('organizer')->nullable();
            $table->string('level')->nullable();
            $table->string('achievement')->nullable();
            $table->text('description')->nullable();
            $table->date('started_on')->nullable();
            $table->date('ended_on')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('certificate_file')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('verification_note')->nullable();
            $table->timestamps();

            $table->index(['skpi_submission_id', 'category']);
            $table->index(['category', 'is_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skpi_items');
    }
};
