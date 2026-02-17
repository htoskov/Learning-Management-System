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
            // Trophies: 0..100 (we'll enforce max in app + optional DB CHECK if you want later)
            $table->unsignedTinyInteger('trophies')->default(0);

            // Video progression: which video user has reached/unlocked (1..)
            $table->unsignedSmallInteger('video_progress')->default(1);

            // Where the user stopped watching (seconds into the current video)
            $table->unsignedInteger('video_time_seconds')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trophies', 'video_progress', 'video_time_seconds']);
        });
    }
};
