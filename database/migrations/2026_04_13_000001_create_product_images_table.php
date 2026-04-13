<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        if (Schema::hasTable('products')) {
            $products = DB::table('products')
                ->select('id', 'image')
                ->whereNotNull('image')
                ->orderBy('id')
                ->get();

            foreach ($products as $product) {
                DB::table('product_images')->insert([
                    'product_id' => $product->id,
                    'path' => $product->image,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
