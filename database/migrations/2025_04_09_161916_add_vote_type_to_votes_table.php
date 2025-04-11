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
        Schema::table('votes', function (Blueprint $table) {
            // Check if vote_type column doesn't already exist before adding it
            if (!Schema::hasColumn('votes', 'vote_type')) {
                $table->string('vote_type')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            if (Schema::hasColumn('votes', 'vote_type')) {
                $table->dropColumn('vote_type');
            }
        });
    }
};
