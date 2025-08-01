<?php

/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table = DB::table('settings');

        if (!$table->where('name', 'default_locale')->exists()) {
            $table->insert([
                'name' => 'default_locale',
                'value' => 'en_US',
            ]);
        }

        if (!$table->where('name', 'company_email')->exists()) {
            $table->insert([
                'name' => 'default_timezone',
                'value' => 'UTC',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = DB::table('settings');

        $table->whereIn('name', ['default_locale', 'default_timezone'])->delete();
    }
};
