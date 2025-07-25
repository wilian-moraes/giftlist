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
        Schema::table('hostnames', function (Blueprint $table) {
            $table->foreign(['hostid'], 'hostnames_hostid_fkey')->references(['id'])->on('hosts')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hostnames', function (Blueprint $table) {
            $table->dropForeign('hostnames_hostid_fkey');
        });
    }
};
