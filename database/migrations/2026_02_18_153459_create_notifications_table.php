<?php
// database/migrations/2024_01_01_000001_create_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();

            $table->unsignedBigInteger('notification_template_id')->nullable()->index();

            $table->json('recipients');
            $table->json('payload')->nullable();
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->json('error_trace')->nullable();
            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(3);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['status', 'scheduled_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['tenant_id', 'status']);

            $table->foreign('notification_template_id')
                ->references('id')
                ->on('notification_templates')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
