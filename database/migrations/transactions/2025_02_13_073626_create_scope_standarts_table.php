<?php

use App\Models\Transaction\Project;
use App\Models\Unit;
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
        Schema::connection('transaction')->create('scope_standarts', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->string('link')->nullable();
            $table->foreignIdFor(Unit::class);
            $table->foreignIdFor(Project::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('transaction')->dropIfExists('scope_standarts');
    }
};
