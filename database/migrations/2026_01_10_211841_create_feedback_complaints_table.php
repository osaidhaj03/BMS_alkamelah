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
        Schema::create('feedback_complaints', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['complaint', 'suggestion', 'feedback', 'inquiry'])->default('feedback');
            $table->enum('category', ['book', 'author', 'search', 'page', 'general'])->default('general');
            $table->string('subject');
            $table->text('message');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('related_type')->nullable(); // Book, Author, etc.
            $table->unsignedBigInteger('related_id')->nullable();
            $table->enum('status', ['pending', 'under_review', 'resolved', 'rejected'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('admin_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_complaints');
    }
};
