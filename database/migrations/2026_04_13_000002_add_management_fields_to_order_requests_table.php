<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_requests', function (Blueprint $table) {
            $table->text('admin_note')->nullable()->after('note');
            $table->timestamp('status_updated_at')->nullable()->after('admin_seen_at');
            $table->index('status_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('order_requests', function (Blueprint $table) {
            $table->dropIndex(['status_updated_at']);
            $table->dropColumn(['admin_note', 'status_updated_at']);
        });
    }
};
