<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JadwalPpdb;
use App\Models\Pengumuman;
use App\Models\ProfilSekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@sdit-binaulummah.test'],
            [
                'name' => 'Admin PPDB',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $profil = [
            'nama_sekolah' => 'SDIT Binaul Ummah',
            'tagline' => 'Belajar, berkarakter, dan bertumbuh bersama.',
            'deskripsi' => 'SDIT Binaul Ummah membuka pendaftaran siswa baru melalui sistem PPDB online yang memudahkan orang tua mendapatkan informasi sekolah, jadwal, dan status pendaftaran secara terpusat.',
            'visi' => 'Menjadi sekolah dasar Islam terpadu yang unggul dalam akhlak, ilmu, dan kemandirian.',
            'misi' => "Menyelenggarakan pembelajaran aktif, Islami, dan ramah anak.\nMembangun karakter siswa melalui pembiasaan ibadah dan akhlak mulia.\nMenjalin komunikasi yang baik antara sekolah dan orang tua.",
            'fasilitas' => "Ruang kelas nyaman\nLingkungan belajar Islami\nArea bermain siswa\nPerpustakaan dan fasilitas pembelajaran",
            'program_unggulan' => "Tahfidz dan pembiasaan ibadah\nKurikulum Islam terpadu\nPembinaan karakter siswa\nKegiatan ekstrakurikuler",
            'kontak' => 'Panitia PPDB SDIT Binaul Ummah',
        ];

        foreach ($profil as $key => $value) {
            ProfilSekolah::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $jadwals = [
            ['tahapan' => 'Pembukaan Pendaftaran', 'tanggal_mulai' => now()->startOfMonth(), 'tanggal_selesai' => now()->startOfMonth()->addDays(20), 'urutan' => 1, 'keterangan' => 'Orang tua dapat membuat akun dan mengisi formulir calon siswa.'],
            ['tahapan' => 'Verifikasi Berkas', 'tanggal_mulai' => now()->startOfMonth()->addDays(21), 'tanggal_selesai' => now()->startOfMonth()->addDays(28), 'urutan' => 2, 'keterangan' => 'Panitia melakukan pengecekan kelengkapan dokumen pendaftaran.'],
            ['tahapan' => 'Observasi/Wawancara', 'tanggal_mulai' => now()->startOfMonth()->addMonth(), 'tanggal_selesai' => now()->startOfMonth()->addMonth()->addDays(7), 'urutan' => 3, 'keterangan' => 'Calon siswa mengikuti observasi sesuai jadwal dari sekolah.'],
            ['tahapan' => 'Pengumuman Hasil', 'tanggal_mulai' => now()->startOfMonth()->addMonth()->addDays(10), 'tanggal_selesai' => null, 'urutan' => 4, 'keterangan' => 'Hasil penerimaan diumumkan melalui dashboard orang tua.'],
        ];

        foreach ($jadwals as $jadwal) {
            JadwalPpdb::updateOrCreate(['tahapan' => $jadwal['tahapan']], $jadwal);
        }

        Pengumuman::updateOrCreate(
            ['judul' => 'PPDB SDIT Binaul Ummah Telah Dibuka'],
            [
                'admin_id' => $admin->id,
                'konten' => 'Pendaftaran siswa baru dapat dilakukan melalui sistem PPDB online. Silakan buat akun orang tua dan lengkapi data calon siswa.',
                'tanggal_publish' => now()->toDateString(),
            ]
        );
    }
}
