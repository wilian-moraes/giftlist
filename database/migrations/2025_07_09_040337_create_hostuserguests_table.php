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
        Schema::create('hostuserguests', function (Blueprint $table) {
            $table->uuid('hostid');
            $table->uuid('userid');

            $table->primary(['hostid','userid']);
            $table->timestamps();

            $table->foreign('hostid')->references('id')->on('hosts')->onDelete('cascade');
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostuserguests');
    }
};
