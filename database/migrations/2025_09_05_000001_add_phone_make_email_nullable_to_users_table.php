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
        // Add phone if it doesn't exist
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->unique()->after('name');
            });
        }

        // Try to make email nullable + unique if column exists (guarded for sqlite/change limitations)
        if (Schema::hasColumn('users', 'email')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('email')->nullable()->unique()->change();
                });
            } catch (\Throwable $e) {
                // On some drivers (e.g., SQLite), change() may not be supported without rebuilding the table.
                // It's safe to skip here; email can remain as-is.
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert email constraint if possible
        if (Schema::hasColumn('users', 'email')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('email')->nullable(false)->unique()->change();
                });
            } catch (\Throwable $e) {
                // ignore if driver doesn't support change()
            }
        }

        // Drop phone if it exists (and drop unique index if present)
        if (Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                try {
                    $table->dropUnique('users_phone_unique');
                } catch (\Throwable $e) {
                    // index name may differ per driver; ignore if not present
                }
                $table->dropColumn('phone');
            });
        }
    }
};
