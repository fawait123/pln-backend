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
        Schema::connection('transaction')->create('documents', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuidMorphs('document');
            $table->string('document_name');
            $table->string('document_link');
            $table->integer('document_size');
            $table->string('document_mime_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('transaction')->dropIfExists('documents');
    }
};
