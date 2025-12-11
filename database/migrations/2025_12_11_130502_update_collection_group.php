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
        //
        Schema::table('contribution_groups', function (Blueprint $table) {
            $table->decimal('individualAmount', 15, 2);
            $table->enum('frequency', ['weekly', 'monthly', 'yearly']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('contribution_groups', function (Blueprint $table) {
            $table->dropColumn('individualAmount');
            $table->dropColumn('frequency');
        });
    }
};
