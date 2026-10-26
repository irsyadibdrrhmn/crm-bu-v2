<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Students table (Siswa aktif SMA Binaul Ummah)
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // student account
            $table->foreignId('parent_id')->nullable()->constrained('users')->nullOnDelete(); // parent account
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('nama_lengkap');
            $table->string('kelas', 30); // e.g. X-MIPA-1, XI-IPS-2, XII-MIPA-3
            $table->string('jurusan', 30)->default('MIPA'); // MIPA / IPS
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('tahun_ajaran', 20)->default('2025/2026');
            $table->string('no_hp_ortu', 30)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // 2. Complaint Tickets (Aspirasi & Layanan CRM)
        Schema::create('complaint_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 30)->unique(); // e.g. TKT-202610-001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Pelapor (student / parent / guru / bk)
            $table->string('pelapor_role', 20)->default('parent');
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->enum('kategori', [
                'akademik',
                'fasilitas',
                'keuangan',
                'kedisiplinan',
                'layanan_konseling',
                'administrasi',
                'ekstrakurikuler'
            ])->default('akademik');
            $table->enum('urgensi', ['rendah', 'sedang', 'tinggi', 'darurat'])->default('sedang');
            $table->unsignedInteger('sla_target_hours')->default(48);
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->decimal('skor_prioritas', 5, 2)->nullable();
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('faktor_penyebab')->nullable();
            $table->text('tindakan_solusi')->nullable();
            $table->string('lampiran')->nullable();
            $table->unsignedTinyInteger('rating_kepuasan')->nullable(); // 1 - 5 (CSAT)
            $table->text('ulasan_kepuasan')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // Staff / Admin
            $table->timestamp('first_responded_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        // 3. Complaint Responses (Histori Komunikasi / Tiket Chat)
        Schema::create('complaint_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_ticket_id')->constrained('complaint_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('pesan');
            $table->string('lampiran')->nullable();
            $table->boolean('is_internal_note')->default(false); // Catatan staf internal
            $table->timestamps();
        });

        // 4. Academic Reports (Nilai & Evaluasi Real-Time)
        Schema::create('academic_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('semester', 20)->default('Ganjil'); // Ganjil / Genap
            $table->string('tahun_ajaran', 20)->default('2025/2026');
            $table->string('mata_pelajaran', 60);
            $table->decimal('kkm', 5, 2)->default(75.00);
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('grade', 5)->default('B');
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
        });

        // 5. Attendances (Presensi Real-Time)
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
            $table->time('jam_masuk')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 6. Announcements (Pengumuman Sekolah)
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->text('konten');
            $table->enum('target_audience', ['semua', 'siswa', 'orang_tua'])->default('semua');
            $table->date('tanggal_publish');
            $table->timestamps();
        });

        // 7. Communication Logs (Log otomatis / audit trail notifikasi layanan)
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('channel', 30)->default('email'); // email, system, sms
            $table->string('subject');
            $table->text('content');
            $table->enum('status', ['terkirim', 'gagal', 'menunggu'])->default('terkirim');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_logs');
        Schema::dropIfExists('pengumumans');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('academic_reports');
        Schema::dropIfExists('complaint_responses');
        Schema::dropIfExists('complaint_tickets');
        Schema::dropIfExists('students');
    }
};

