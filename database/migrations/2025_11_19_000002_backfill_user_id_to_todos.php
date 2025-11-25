<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill all existing todos with user_id = 1 (admin user)
        DB::table('todos')->whereNull('user_id')->update(['user_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset user_id to null for todos that were backfilled
        DB::table('todos')->where('user_id', 1)->whereIn('id', DB::table('todos')->where('user_id', 1)->pluck('id'))->update(['user_id' => null]);
    }
};
