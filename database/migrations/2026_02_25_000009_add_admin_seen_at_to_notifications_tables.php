<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_requests', function (Blueprint $table) {
            $table->timestamp('admin_seen_at')->nullable()->after('status');
            $table->index('admin_seen_at', 'order_requests_admin_seen_at_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->timestamp('admin_seen_at')->nullable()->after('is_approved');
            $table->index('admin_seen_at', 'reviews_admin_seen_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('order_requests', function (Blueprint $table) {
            $table->dropIndex('order_requests_admin_seen_at_index');
            $table->dropColumn('admin_seen_at');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_admin_seen_at_index');
            $table->dropColumn('admin_seen_at');
        });
    }
};
