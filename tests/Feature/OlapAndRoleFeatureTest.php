<?php

namespace Tests\Feature;

use App\Models\ComplaintTicket;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OlapAndRoleFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_olap_analytics_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('analytics.olap'));

        $response->assertStatus(200);
        $response->assertSee('Pemrosesan Analitis Multidimensi');
        $response->assertSee('Fase 1: Acquire');
        $response->assertSee('Fase 2: Enhance');
        $response->assertSee('Fase 3: Retain');
    }

    public function test_guru_and_bk_can_access_olap_analytics(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $bk = User::factory()->create(['role' => 'bk']);

        $this->actingAs($guru)->get(route('analytics.olap'))->assertStatus(200);
        $this->actingAs($bk)->get(route('analytics.olap'))->assertStatus(200);
    }

    public function test_parent_and_student_cannot_access_olap_analytics(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($parent)->get(route('analytics.olap'))->assertStatus(403);
        $this->actingAs($student)->get(route('analytics.olap'))->assertStatus(403);
    }

    public function test_guru_can_create_ticket_for_student_with_olap_priority(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $student = Student::create([
            'nis' => '20241099',
            'nama_lengkap' => 'Ahmad Fulan',
            'kelas' => 'XI-MIPA-1',
            'jurusan' => 'MIPA',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($guru)->post(route('tickets.store'), [
            'student_id' => $student->id,
            'kategori' => 'layanan_konseling',
            'urgensi' => 'tinggi',
            'judul' => 'Penurunan Motivasi Belajar dan Membolos Siswa',
            'deskripsi' => 'Siswa telah membolos 2 kali pada jam pelajaran fisika.',
            'faktor_penyebab' => 'Kebiasaan begadang bermain gim.',
            'tindakan_solusi' => 'Pemanggilan bimbingan konseling dan wali murid.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('complaint_tickets', [
            'user_id' => $guru->id,
            'pelapor_role' => 'guru',
            'student_id' => $student->id,
            'kategori' => 'layanan_konseling',
            'urgensi' => 'tinggi',
            'faktor_penyebab' => 'Kebiasaan begadang bermain gim.',
            'tindakan_solusi' => 'Pemanggilan bimbingan konseling dan wali murid.',
        ]);

        $ticket = ComplaintTicket::where('user_id', $guru->id)->first();
        $this->assertNotNull($ticket->skor_prioritas);
        $this->assertEquals(24, $ticket->sla_target_hours);
    }

    public function test_admin_can_create_new_guru_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Bapak Joko Santoso, M.Pd',
            'email' => 'joko.guru@sekolah.sch.id',
            'role' => 'guru',
            'nip' => '198203152006041002',
            'jabatan' => 'Guru Matematika & Wali Kelas X-MIPA-2',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'joko.guru@sekolah.sch.id',
            'role' => 'guru',
            'nip' => '198203152006041002',
        ]);
    }
}

