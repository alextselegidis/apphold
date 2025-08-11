<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('observers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->text('title')->nullable();
            $table->string('url');
            $table->integer('interval')->default(60);
            $table->text('emails')->nullable();
            $table->string('notes')->nullable();
            $table->longText('favicon')->charset('binary')->nullable(); // LONGBLOB
            $table->longText('og_image')->charset('binary')->nullable(); // LONGBLOB
            $table->boolean('is_active')->default(true);
            $table->boolean('with_ssl_verification')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observers');
    }
};
