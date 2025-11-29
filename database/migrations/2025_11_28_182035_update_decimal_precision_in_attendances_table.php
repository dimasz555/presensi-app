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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('check_in_lat', 18, 14)->nullable()->change();
            $table->decimal('check_in_long', 18, 14)->nullable()->change();
            $table->decimal('check_out_lat', 18, 14)->nullable()->change();
            $table->decimal('check_out_long', 18, 14)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('check_in_lat', 10, 7)->nullable()->change();
            $table->decimal('check_in_long', 10, 7)->nullable()->change();
            $table->decimal('check_out_lat', 10, 7)->nullable()->change();
            $table->decimal('check_out_long', 10, 7)->nullable()->change();
        });
    }
};
