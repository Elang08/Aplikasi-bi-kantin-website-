<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $columns = [];

        if (Schema::hasColumn('users', 'password')) {
            $columns[] = 'password';
        }

        if (Schema::hasColumn('users', 'role')) {
            $columns[] = 'role';
        }

        if ($columns === []) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($columns): void {
            if (in_array('password', $columns, true)) {
                $table->string('password', 255)->change();
            }

            if (in_array('role', $columns, true)) {
                $table->string('role', 20)->change();
            }
        });
    }

    public function down(): void
    {
        // Password hashes need more than the legacy 50-character limit.
    }
};
