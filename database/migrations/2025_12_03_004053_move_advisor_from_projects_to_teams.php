<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->foreignId('advisor_id')->nullable()->after('leader_id')->constrained('users');
            $table->string('advisor_status')->default('pending')->after('advisor_id');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['advisor_id']);
            $table->dropColumn(['advisor_id', 'advisor_status']);
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('advisor_id')->nullable()->constrained('users');
            $table->string('advisor_status')->default('pending');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['advisor_id']);
            $table->dropColumn(['advisor_id', 'advisor_status']);
        });
    }
};
