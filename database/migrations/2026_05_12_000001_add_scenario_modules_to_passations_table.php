<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passations', function (Blueprint $table) {
            if (!Schema::hasColumn('passations', 'scenario')) {
                $table->string('scenario', 2)->nullable()->after('score');
            }

            if (!Schema::hasColumn('passations', 'modules')) {
                $table->json('modules')->nullable()->after('scenario');
            }
        });
    }

    public function down(): void
    {
        Schema::table('passations', function (Blueprint $table) {
            $table->dropColumn(['scenario', 'modules']);
        });
    }
};
