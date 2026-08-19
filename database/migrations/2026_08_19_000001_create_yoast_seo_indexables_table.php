<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yoast_seo_indexables', function (Blueprint $table): void {
            $table->id();
            $table->string('object_type', 100);
            $table->string('object_id', 191);
            $table->string('permalink_hash', 64);
            $table->text('permalink')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('canonical')->nullable();
            $table->string('robots', 500)->nullable();
            $table->boolean('public')->default(true);
            $table->json('open_graph')->nullable();
            $table->json('twitter')->nullable();
            $table->json('schema')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('indexed_at')->nullable();
            $table->timestamps();

            $table->unique(['object_type', 'object_id']);
            $table->unique('permalink_hash');
            $table->index(['public', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yoast_seo_indexables');
    }
};
