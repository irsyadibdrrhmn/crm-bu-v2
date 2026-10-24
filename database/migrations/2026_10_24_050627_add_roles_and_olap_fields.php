<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah role guru & bk pada tabel users (khusus MySQL)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'bk', 'parent', 'student') NOT NULL DEFAULT 'parent'");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 30)->nullable()->after('email');
            $table->string('jabatan', 100)->nullable()->after('nip'); // e.g. Guru BK, Wali Kelas X-MIPA-1
        });

        // 2. Tambah field OLAP & analitik penyelesaian masalah pada tiket
        Schema::table('complaint_tickets', function (Blueprint $table) {
            $table->string('pelapor_role', 20)->default('parent')->after('user_id'); // parent, student, guru, bk, admin
            $table->unsignedInteger('sla_target_hours')->default(48)->after('urgensi'); // SLA target jam
            $table->text('faktor_penyebab')->nullable()->after('deskripsi'); // Analitik: Akar / faktor penyebab masalah
            $table->text('tindakan_solusi')->nullable()->after('faktor_penyebab'); // Metode penyelesaian: Tindakan perbaikan yang diambil
            $table->decimal('skor_prioritas', 5, 2)->nullable()->after('status'); // Skor prioritas hasil kalkulasi analitik
        });
    }

    public function down(): void
    {
        Schema::table('complaint_tickets', function (Blueprint $table) {
            $table->dropColumn(['pelapor_role', 'sla_target_hours', 'faktor_penyebab', 'tindakan_solusi', 'skor_prioritas']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'jabatan']);
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'parent', 'student') NOT NULL DEFAULT 'parent'");
    }
};
