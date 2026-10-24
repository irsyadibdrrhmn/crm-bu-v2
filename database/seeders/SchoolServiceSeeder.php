<?php

namespace Database\Seeders;

use App\Models\AcademicReport;
use App\Models\Attendance;
use App\Models\ComplaintResponse;
use App\Models\ComplaintTicket;
use App\Models\CommunicationLog;
use App\Models\Pengumuman;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SchoolServiceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin / Manajemen Sekolah
        $admin = User::firstOrCreate(
            ['email' => 'admin@sma-binaulummah.sch.id'],
            [
                'name' => 'Dr. H. Ahmad Fauzi, M.Pd (Kepala Sekolah & Manajemen)',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $staffAdmin = User::firstOrCreate(
            ['email' => 'layanan@sma-binaulummah.sch.id'],
            [
                'name' => 'Siti Rahmawati, S.Kom (Koordinator Layanan Terpadu)',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // 1b. Buat User Guru & Guru BP/BK (Sesuai masukan penguji)
        $guru1 = User::firstOrCreate(
            ['email' => 'guru@sma-binaulummah.sch.id'],
            [
                'name' => 'Drs. Hidayat, M.Si (Wali Kelas XI-MIPA-1)',
                'nip' => '197505122002121003',
                'jabatan' => 'Wali Kelas & Guru Fisika',
                'role' => 'guru',
                'password' => Hash::make('password'),
            ]
        );

        $guruBk = User::firstOrCreate(
            ['email' => 'bk@sma-binaulummah.sch.id'],
            [
                'name' => 'Nurul Hidayati, S.Psi., M.Pd (Guru Konselor BP/BK)',
                'nip' => '198402152009032005',
                'jabatan' => 'Koordinator Bimbingan Konseling (BP/BK)',
                'role' => 'bk',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Buat User Parents (Orang Tua / Wali)
        $parent1 = User::firstOrCreate(
            ['email' => 'bambang.wali@gmail.com'],
            [
                'name' => 'Ir. Bambang Soediro',
                'role' => 'parent',
                'password' => Hash::make('password'),
            ]
        );

        $parent2 = User::firstOrCreate(
            ['email' => 'dewi.safitri@gmail.com'],
            [
                'name' => 'Dra. Dewi Safitri, M.M',
                'role' => 'parent',
                'password' => Hash::make('password'),
            ]
        );

        // 3. Buat User Students (Siswa)
        $studentUser1 = User::firstOrCreate(
            ['email' => 'raditya.arya@siswa.sma-binaulummah.sch.id'],
            [
                'name' => 'Raditya Arya Pratama',
                'role' => 'student',
                'password' => Hash::make('password'),
            ]
        );

        $studentUser2 = User::firstOrCreate(
            ['email' => 'nabilah.azzahra@siswa.sma-binaulummah.sch.id'],
            [
                'name' => 'Nabilah Azzahra',
                'role' => 'student',
                'password' => Hash::make('password'),
            ]
        );

        // 4. Buat Profil Siswa
        $student1 = Student::updateOrCreate(
            ['nis' => '20241001'],
            [
                'user_id' => $studentUser1->id,
                'parent_id' => $parent1->id,
                'nisn' => '0081234501',
                'nama_lengkap' => 'Raditya Arya Pratama',
                'kelas' => 'XI-MIPA-1',
                'jurusan' => 'MIPA',
                'jenis_kelamin' => 'L',
                'tahun_ajaran' => '2025/2026',
                'no_hp_ortu' => '081234567890',
                'status_aktif' => true,
            ]
        );

        $student2 = Student::updateOrCreate(
            ['nis' => '20241002'],
            [
                'user_id' => $studentUser2->id,
                'parent_id' => $parent2->id,
                'nisn' => '0081234502',
                'nama_lengkap' => 'Nabilah Azzahra',
                'kelas' => 'XI-MIPA-1',
                'jurusan' => 'MIPA',
                'jenis_kelamin' => 'P',
                'tahun_ajaran' => '2025/2026',
                'no_hp_ortu' => '081398765432',
                'status_aktif' => true,
            ]
        );

        // Siswa dummy tambahan untuk OLAP analytics
        $dummyStudentsData = [
            ['nis' => '20241003', 'nama' => 'Muhammad Fikri Alamsyah', 'kelas' => 'X-MIPA-2', 'jurusan' => 'MIPA', 'jk' => 'L'],
            ['nis' => '20241004', 'nama' => 'Aisyah Putri Maharani', 'kelas' => 'X-IPS-1', 'jurusan' => 'IPS', 'jk' => 'P'],
            ['nis' => '20241005', 'nama' => 'Zaky Raihan Syahputra', 'kelas' => 'XII-MIPA-1', 'jurusan' => 'MIPA', 'jk' => 'L'],
            ['nis' => '20241006', 'nama' => 'Clarissa Aurelia', 'kelas' => 'XI-IPS-2', 'jurusan' => 'IPS', 'jk' => 'P'],
            ['nis' => '20241007', 'nama' => 'Dimas Maulana Hidayat', 'kelas' => 'XII-IPS-1', 'jurusan' => 'IPS', 'jk' => 'L'],
        ];

        $extraStudents = [];
        foreach ($dummyStudentsData as $ds) {
            $extraStudents[] = Student::updateOrCreate(
                ['nis' => $ds['nis']],
                [
                    'user_id' => null,
                    'parent_id' => null,
                    'nisn' => '0089' . rand(100000, 999999),
                    'nama_lengkap' => $ds['nama'],
                    'kelas' => $ds['kelas'],
                    'jurusan' => $ds['jurusan'],
                    'jenis_kelamin' => $ds['jk'],
                    'tahun_ajaran' => '2025/2026',
                    'no_hp_ortu' => '0821' . rand(10000000, 99999999),
                    'status_aktif' => true,
                ]
            );
        }

        $allStudents = array_merge([$student1, $student2], $extraStudents);

        // 5. Seeding Laporan Nilai Akademik
        $mapelList = [
            ['nama' => 'Matematika Peminatan', 'kkm' => 75],
            ['nama' => 'Fisika', 'kkm' => 75],
            ['nama' => 'Kimia', 'kkm' => 75],
            ['nama' => 'Biologi', 'kkm' => 75],
            ['nama' => 'Bahasa Inggris', 'kkm' => 78],
            ['nama' => 'Pendidikan Agama Islam', 'kkm' => 80],
            ['nama' => 'Bahasa Indonesia', 'kkm' => 78],
        ];

        foreach ($allStudents as $st) {
            foreach ($mapelList as $mp) {
                $tugas = rand(70, 95);
                $uts = rand(65, 96);
                $uas = rand(70, 98);
                $akhir = round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 1);
                $grade = $akhir >= 88 ? 'A' : ($akhir >= 78 ? 'B' : ($akhir >= 68 ? 'C' : 'D'));

                AcademicReport::updateOrCreate(
                    [
                        'student_id' => $st->id,
                        'mata_pelajaran' => $mp['nama'],
                        'semester' => 'Ganjil',
                        'tahun_ajaran' => '2025/2026',
                    ],
                    [
                        'kkm' => $mp['kkm'],
                        'nilai_tugas' => $tugas,
                        'nilai_uts' => $uts,
                        'nilai_uas' => $uas,
                        'nilai_akhir' => $akhir,
                        'grade' => $grade,
                        'catatan_guru' => $akhir < $mp['kkm'] ? 'Perlu bimbingan dan remedial terarah.' : 'Progres belajar konsisten dan aktif di kelas.',
                    ]
                );
            }
        }

        // 6. Seeding Presensi Harian (14 hari terakhir)
        foreach ($allStudents as $st) {
            for ($i = 14; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                if ($date->isWeekend()) {
                    continue;
                }

                $rand = rand(1, 100);
                $status = 'hadir';
                $jam = '06:4' . rand(0, 9);
                $ket = null;

                if ($rand > 95) {
                    $status = 'sakit';
                    $jam = null;
                    $ket = 'Surat keterangan dokter terlampir';
                } elseif ($rand > 90) {
                    $status = 'izin';
                    $jam = null;
                    $ket = 'Kepentingan keluarga penting';
                }

                Attendance::updateOrCreate(
                    [
                        'student_id' => $st->id,
                        'tanggal' => $date->toDateString(),
                    ],
                    [
                        'status' => $status,
                        'jam_masuk' => $jam,
                        'keterangan' => $ket,
                    ]
                );
            }
        }

        // 7. Seeding Tiket Pengaduan & Aspirasi Layanan CRM
        $ticketsData = [
            [
                'ticket_number' => 'TKT-202610-001',
                'user_id' => $parent1->id,
                'pelapor_role' => 'parent',
                'student_id' => $student1->id,
                'kategori' => 'akademik',
                'urgensi' => 'sedang',
                'status' => 'selesai',
                'judul' => 'Konfirmasi Penyesuaian Jadwal Remedial Fisika',
                'deskripsi' => 'Mohon informasi jadwal remedial ujian harian Fisika kelas XI-MIPA-1 agar ananda dapat mempersiapkan materi lebih optimal.',
                'faktor_penyebab' => 'Ketidaksinkronan jadwal remedial dengan kegiatan praktikum siswa.',
                'tindakan_solusi' => 'Guru mapel telah mengunggah jadwal remedial susulan di papan pengumuman akademik.',
                'skor_prioritas' => 65.00,
                'rating' => 5,
                'ulasan' => 'Pelayanan cepat dan penjelasan jadwal dari guru piket sangat jelas.',
                'assigned_to' => $staffAdmin->id,
                'first_response' => Carbon::now()->subDays(4)->addHours(2),
                'resolved' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'ticket_number' => 'TKT-202610-002',
                'user_id' => $studentUser1->id,
                'pelapor_role' => 'student',
                'student_id' => $student1->id,
                'kategori' => 'fasilitas',
                'urgensi' => 'tinggi',
                'status' => 'diproses',
                'judul' => 'AC Laboratorium Komputer Sering Mati Mendadak',
                'deskripsi' => 'Saat praktikum informatika di Lab 2, suhu ruangan sangat panas karena pendingin ruangan sering mati sendiri. Mohon perbaikan teknisi.',
                'faktor_penyebab' => 'Kapasitor unit AC outdoor mengalami penurunan daya kerja (overheat).',
                'tindakan_solusi' => 'Surat perintah kerja (SPK) perbaikan telah diterbitkan ke teknisi sarpras sekolah.',
                'skor_prioritas' => 85.00,
                'rating' => null,
                'ulasan' => null,
                'assigned_to' => $staffAdmin->id,
                'first_response' => Carbon::now()->subDays(2)->addHours(1),
                'resolved' => null,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'ticket_number' => 'TKT-202610-003',
                'user_id' => $parent2->id,
                'pelapor_role' => 'parent',
                'student_id' => $student2->id,
                'kategori' => 'administrasi',
                'urgensi' => 'sedang',
                'status' => 'selesai',
                'judul' => 'Permohonan Legalisir Rapor Semester 1 & 2 untuk Lomba',
                'deskripsi' => 'Ananda membutuhkan salinan rapor terlegalisir untuk kelengkapan administrasi Olimpiade Sains Nasional tingkat Kabupaten.',
                'faktor_penyebab' => 'Kebutuhan berkas resmi untuk verifikasi panitia OSN tingkat kabupaten.',
                'tindakan_solusi' => 'Pencetakan dan cap stempel basah kepala sekolah selesai dalam 2 jam.',
                'skor_prioritas' => 60.00,
                'rating' => 5,
                'ulasan' => 'Dokumen sudah diambil di loket TU dengan sangat cepat. Terima kasih.',
                'assigned_to' => $admin->id,
                'first_response' => Carbon::now()->subDays(5)->addMinutes(45),
                'resolved' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'ticket_number' => 'TKT-202610-004',
                'user_id' => $studentUser2->id,
                'pelapor_role' => 'student',
                'student_id' => $student2->id,
                'kategori' => 'ekstrakurikuler',
                'urgensi' => 'rendah',
                'status' => 'menunggu',
                'judul' => 'Usulan Penambahan Alat Musik untuk Ekskul Seni Musik',
                'deskripsi' => 'Kami dari pengurus ekskul musik ingin mengajukan peremajaan senar gitar dan stand keyboard untuk persiapan pensi sekolah.',
                'faktor_penyebab' => 'Usia pakai alat musik ekskul yang sudah lebih dari 2 tahun.',
                'tindakan_solusi' => null,
                'skor_prioritas' => 40.00,
                'rating' => null,
                'ulasan' => null,
                'assigned_to' => null,
                'first_response' => null,
                'resolved' => null,
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'ticket_number' => 'TKT-202610-005',
                'user_id' => $parent1->id,
                'pelapor_role' => 'parent',
                'student_id' => $student1->id,
                'kategori' => 'layanan_konseling',
                'urgensi' => 'sedang',
                'status' => 'diproses',
                'judul' => 'Konsultasi Penjurusan Perguruan Tinggi Negeri (SNBP)',
                'deskripsi' => 'Ingin menjadwalkan sesi bimbingan karir bersama guru BK untuk pemetaan nilai rapor ananda ke target prodi universitas.',
                'faktor_penyebab' => 'Kebingungan memilih peminatan prodi saintek vs soshum berdasarkan tren nilai rapor.',
                'tindakan_solusi' => 'Dijadwalkan sesi konsultasi offline bersama Guru BP/BK hari Selasa jam 13.00 WIB.',
                'skor_prioritas' => 70.00,
                'rating' => null,
                'ulasan' => null,
                'assigned_to' => $guruBk->id,
                'first_response' => Carbon::now()->subHours(12),
                'resolved' => null,
                'created_at' => Carbon::now()->subHours(18),
            ],
            // Tiket Baru diinputkan oleh Guru Mata Pelajaran / Wali Kelas (Sesuai revisi penguji)
            [
                'ticket_number' => 'TKT-202610-006',
                'user_id' => $guru1->id,
                'pelapor_role' => 'guru',
                'student_id' => $student1->id,
                'kategori' => 'akademik',
                'urgensi' => 'sedang',
                'status' => 'diproses',
                'judul' => 'Laporan Evaluasi Prestasi & Kesulitan Belajar Fisika Ananda',
                'deskripsi' => 'Sebagai wali kelas dan pengampu mapel Fisika, saya mengamati ananda sering mengalami kesulitan pada materi Dinamika Rotasi. Perlu koordinasi bimbingan tambahan dan pendampingan belajar di rumah.',
                'faktor_penyebab' => 'Kurangnya pemahaman konsep dasar kalkulus dan latihan soal mandiri di luar jam sekolah.',
                'tindakan_solusi' => 'Pemberian modul latihan terstruktur dan koordinasi dengan orang tua via komunikasi portal.',
                'skor_prioritas' => 72.50,
                'rating' => null,
                'ulasan' => null,
                'assigned_to' => $staffAdmin->id,
                'first_response' => Carbon::now()->subHours(5),
                'resolved' => null,
                'created_at' => Carbon::now()->subHours(8),
            ],
            // Tiket Baru diinputkan oleh Guru BP/BK (Sesuai revisi penguji)
            [
                'ticket_number' => 'TKT-202610-007',
                'user_id' => $guruBk->id,
                'pelapor_role' => 'bk',
                'student_id' => $student2->id,
                'kategori' => 'layanan_konseling',
                'urgensi' => 'tinggi',
                'status' => 'diproses',
                'judul' => 'Catatan Kasus Konseling & Keterlambatan Masuk Sekolah Berulang',
                'deskripsi' => 'Berdasarkan rekap data presensi real-time, ananda tercatat terlambat 3 kali berturut-turut pada minggu ini. Guru BK telah melakukan sesi bimbingan individual untuk menggali kendala psikologis maupun teknis.',
                'faktor_penyebab' => 'Siswa memiliki kendala pola tidur karena begadang tugas dan jarak tempat tinggal yang jauh.',
                'tindakan_solusi' => 'Penyusunan jadwal manajemen waktu harian siswa serta pemanggilan orang tua untuk monitoring bersama.',
                'skor_prioritas' => 88.00,
                'rating' => null,
                'ulasan' => null,
                'assigned_to' => $guruBk->id,
                'first_response' => Carbon::now()->subHours(3),
                'resolved' => null,
                'created_at' => Carbon::now()->subHours(4),
            ],
        ];

        foreach ($ticketsData as $td) {
            $ticket = ComplaintTicket::updateOrCreate(
                ['ticket_number' => $td['ticket_number']],
                [
                    'user_id' => $td['user_id'],
                    'pelapor_role' => $td['pelapor_role'] ?? 'parent',
                    'student_id' => $td['student_id'],
                    'kategori' => $td['kategori'],
                    'urgensi' => $td['urgensi'],
                    'status' => $td['status'],
                    'judul' => $td['judul'],
                    'deskripsi' => $td['deskripsi'],
                    'faktor_penyebab' => $td['faktor_penyebab'] ?? null,
                    'tindakan_solusi' => $td['tindakan_solusi'] ?? null,
                    'skor_prioritas' => $td['skor_prioritas'] ?? null,
                    'rating_kepuasan' => $td['rating'],
                    'ulasan_kepuasan' => $td['ulasan'],
                    'assigned_to' => $td['assigned_to'],
                    'first_responded_at' => $td['first_response'],
                    'resolved_at' => $td['resolved'],
                    'created_at' => $td['created_at'],
                ]
            );

            // Respon percakapan tiket
            if ($td['first_response']) {
                ComplaintResponse::updateOrCreate(
                    [
                        'complaint_ticket_id' => $ticket->id,
                        'user_id' => $td['assigned_to'],
                    ],
                    [
                        'pesan' => 'Halo, terima kasih telah menghubungi Layanan Terpadu SMA Binaul Ummah. Laporan Anda telah kami verifikasi dan langsung dikoordinasikan dengan tim terkait.',
                        'is_internal_note' => false,
                        'created_at' => $td['first_response'],
                    ]
                );
            }
        }

        // 8. Seeding Pengumuman Sekolah SMA Binaul Ummah
        $announcements = [
            [
                'judul' => 'Jadwal Penilaian Tengah Semester (PTS) Ganjil TA 2025/2026',
                'konten' => 'Diberitahukan kepada seluruh siswa dan bapak/ibu orang tua wali, pelaksanaan PTS Ganjil akan dimulai tanggal 12 hingga 20 Oktober 2026. Jadwal lengkap mata pelajaran dan nomor ruang dapat diunduh pada portal akademik.',
                'target_audience' => 'semua',
                'tanggal_publish' => Carbon::now()->subDays(2)->toDateString(),
            ],
            [
                'judul' => 'Sosialisasi Pemetaan Minat Bakat & Persiapan UTBK Kelas XI dan XII',
                'konten' => 'Biro Bimbingan Konseling menyelenggarakan sosialisasi pemetaan jurusan perguruan tinggi negeri pada hari Sabtu pukul 08.30 WIB via Zoom Webinar. Tautan undangan dikirimkan melalui email masing-masing orang tua.',
                'target_audience' => 'orang_tua',
                'tanggal_publish' => Carbon::now()->subDays(5)->toDateString(),
            ],
            [
                'judul' => 'Pendaftaran Lomba Tahfidz dan Sains Tingkat Nasional 2026',
                'konten' => 'Siswa yang berminat mengikuti delegasi sekolah pada ajang Festival Sains & Budaya Islam dapat mendaftarkan diri ke bagian kesiswaan paling lambat akhir pekan ini.',
                'target_audience' => 'siswa',
                'tanggal_publish' => Carbon::now()->subDays(8)->toDateString(),
            ],
        ];

        foreach ($announcements as $ann) {
            Pengumuman::updateOrCreate(
                ['judul' => $ann['judul']],
                [
                    'admin_id' => $admin->id,
                    'konten' => $ann['konten'],
                    'target_audience' => $ann['target_audience'],
                    'tanggal_publish' => $ann['tanggal_publish'],
                ]
            );
        }

        // 9. Seeding Communication Logs
        CommunicationLog::create([
            'user_id' => $parent1->id,
            'channel' => 'email',
            'subject' => 'Laporan Rekapitulasi Presensi Mingguan Ananda Raditya Arya',
            'content' => 'Kehadiran ananda Raditya Arya minggu ini tercatat 100% hadir tepat waktu.',
            'status' => 'terkirim',
            'sent_at' => Carbon::now()->subDays(3),
        ]);

        CommunicationLog::create([
            'user_id' => $parent2->id,
            'channel' => 'email',
            'subject' => 'Pembaruan Status Tiket Layanan #TKT-202610-003',
            'content' => 'Permohonan Legalisir Rapor Anda telah diselesaikan oleh loket TU.',
            'status' => 'terkirim',
            'sent_at' => Carbon::now()->subDays(4),
        ]);
    }
}

