<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scriptures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books');
            $table->foreignId('book_version_id')->constrained('book_versions');
            $table->integer('chapter');
            $table->integer('verse');
            $table->string('text');
            $table->string('notes')->nullable();
            $table->string('reference')->nullable()->comment('Bible api reference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scriptures');
    }
};
