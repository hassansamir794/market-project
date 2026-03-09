<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->unsignedBigInteger('count')->default(0);
            $table->timestamp('last_searched_at')->nullable();
            $table->timestamps();

            $table->index('count', 'search_keywords_count_index');
            $table->index('last_searched_at', 'search_keywords_last_searched_at_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_keywords');
    }
};

