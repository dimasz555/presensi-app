<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    public function up(): void
    {
        // Buat role admin jika belum ada
        Role::firstOrCreate(['name' => 'admin']);

        // Buat role karyawan
        Role::firstOrCreate(['name' => 'karyawan']);
    }

    public function down(): void
    {
        Role::where('name', 'admin')->delete();
        Role::where('name', 'karyawan')->delete();
    }
};
