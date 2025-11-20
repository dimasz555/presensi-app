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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->enum('gender', ['l', 'p'])->nullable();
            $table->integer('basic_salary')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->json('face_embedding')->nullable();
            $table->timestamp('face_registered_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('position_id');
            $table->dropColumn([
                'gender',
                'basic_salary',
                'phone',
                'face_embedding',
                'face_registered_at',
                'status'
            ]);
        });
    }
};
