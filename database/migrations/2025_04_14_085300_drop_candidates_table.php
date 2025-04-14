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
        // Supprimer la table candidates après avoir migré les données
        if (Schema::hasTable('candidates')) {
            Schema::dropIfExists('candidates');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la table candidates si nécessaire
        if (!Schema::hasTable('candidates')) {
            Schema::create('candidates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('photo')->nullable();
                $table->boolean('is_finalist')->default(false);
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });
        }
    }
};
