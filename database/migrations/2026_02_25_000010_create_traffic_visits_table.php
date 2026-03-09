<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traffic_visits', function (Blueprint $table) {
            $table->id();
            $table->string('source', 40);
            $table->string('referer_host')->nullable();
            $table->string('path', 255);
            $table->timestamps();

            $table->index('source', 'traffic_visits_source_index');
            $table->index('created_at', 'traffic_visits_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traffic_visits');
    }
};

