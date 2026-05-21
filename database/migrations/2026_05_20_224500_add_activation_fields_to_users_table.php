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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('activation_status', ['pending', 'active', 'rejected'])
                ->default('pending')
                ->after('password');
            $table->timestamp('activation_requested_at')->nullable()->after('activation_status');
            $table->timestamp('activated_at')->nullable()->after('activation_requested_at');
            $table->foreignId('activated_by')->nullable()->after('activated_at')->constrained('users')->nullOnDelete();
            $table->timestamp('activation_rejected_at')->nullable()->after('activated_by');
            $table->text('activation_rejection_note')->nullable()->after('activation_rejected_at');

            $table->index(['activation_status', 'msmhs_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['activation_status', 'msmhs_id']);
            $table->dropConstrainedForeignId('activated_by');
            $table->dropColumn([
                'activation_status',
                'activation_requested_at',
                'activated_at',
                'activation_rejected_at',
                'activation_rejection_note',
            ]);
        });
    }
};
