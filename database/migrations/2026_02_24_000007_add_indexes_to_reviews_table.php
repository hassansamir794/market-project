<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->index('product_id', 'reviews_product_id_index');
            $table->index('rating', 'reviews_rating_index');
            $table->index('is_approved', 'reviews_is_approved_index');
            $table->index('created_at', 'reviews_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_product_id_index');
            $table->dropIndex('reviews_rating_index');
            $table->dropIndex('reviews_is_approved_index');
            $table->dropIndex('reviews_created_at_index');
        });
    }
};
