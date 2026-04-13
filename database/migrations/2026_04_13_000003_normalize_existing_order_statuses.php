<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('order_requests')
            ->where('status', 'contacted')
            ->update([
                'status' => 'confirmed',
                'status_updated_at' => DB::raw('COALESCE(status_updated_at, NOW())'),
            ]);

        DB::table('order_requests')
            ->where('status', 'completed')
            ->update([
                'status' => 'delivered',
                'status_updated_at' => DB::raw('COALESCE(status_updated_at, NOW())'),
            ]);
    }

    public function down(): void
    {
        DB::table('order_requests')
            ->where('status', 'confirmed')
            ->update(['status' => 'contacted']);

        DB::table('order_requests')
            ->where('status', 'delivered')
            ->update(['status' => 'completed']);
    }
};
