<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_submission_id')->constrained()->cascadeOnDelete();

            $table->string('type')->nullable();        // image|video|pdf|doc|excel|txt|other
            $table->string('path');
            $table->string('original_name');
            $table->unsignedBigInteger('size')->default(0);
            $table->string('mime')->nullable();

            $table->timestamps();

            $table->index(['project_submission_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
