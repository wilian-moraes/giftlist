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
        Schema::table('chooseproducts', function (Blueprint $table) {
            $table->foreign(['productid'], 'chooseproducts_productid_fkey')->references(['id'])->on('products')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chooseproducts', function (Blueprint $table) {
            $table->dropForeign('chooseproducts_productid_fkey');
        });
    }
};
