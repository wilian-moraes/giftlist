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
        Schema::table('guestnames', function (Blueprint $table) {
            $table->foreign(['chooseproductid'], 'guestnames_chooseproductid_fkey')->references(['id'])->on('chooseproducts')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guestnames', function (Blueprint $table) {
            $table->dropForeign('guestnames_chooseproductid_fkey');
        });
    }
};
