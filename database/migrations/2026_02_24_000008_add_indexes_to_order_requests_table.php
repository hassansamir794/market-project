<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_requests', function (Blueprint $table) {
            $table->index('product_id', 'order_requests_product_id_index');
            $table->index('status', 'order_requests_status_index');
            $table->index('created_at', 'order_requests_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('order_requests', function (Blueprint $table) {
            $table->dropIndex('order_requests_product_id_index');
            $table->dropIndex('order_requests_status_index');
            $table->dropIndex('order_requests_created_at_index');
        });
    }
};
