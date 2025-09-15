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
        Schema::create('devotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scripture_id')->constrained('scriptures');
            $table->string('personal_reflection')->nullable();
            $table->string('prayer_request')->nullable();
            $table->string('application_notes')->nullable();
            $table->string('mood')->nullable();
            $table->boolean('favorite')->default(false);
            $table->datetime('study_date')->nullable(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devotions');
    }
};
