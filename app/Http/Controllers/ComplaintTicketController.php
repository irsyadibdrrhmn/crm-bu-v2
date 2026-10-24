<?php

namespace App\Http\Controllers;

use App\Models\ComplaintResponse;
use App\Models\ComplaintTicket;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ComplaintTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = ComplaintTicket::with(['user', 'student', 'assignedStaff'])->latest();

        // Filter berdasarkan peran
        if (!$user->isStaff()) {
            $query->where('user_id', $user->id);
        } else {
            // Filter tambahan untuk Staf (Admin, Guru, BK)
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('urgensi')) {
                $query->where('urgensi', $request->urgensi);
            }
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }
            if ($request->filled('pelapor_role')) {
                $query->where('pelapor_role', $request->pelapor_role);
            }
        }

        $tickets = $query->paginate(15)->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $students = collect();

        if ($user->isStaff()) {
            $students = Student::orderBy('kelas')->orderBy('nama_lengkap')->get();
        } elseif ($user->isParent()) {
            $students = $user->children;
        } elseif ($user->isStudent() && $user->studentProfile) {
            $students = collect([$user->studentProfile]);
        }

        $selectedStudentId = $request->query('student_id');

        return view('tickets.create', compact('students', 'selectedStudentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:akademik,fasilitas,keuangan,kedisiplinan,layanan_konseling,administrasi,ekstrakurikuler',
            'urgensi' => 'required|in:rendah,sedang,tinggi,darurat',
            'deskripsi' => 'required|string',
            'faktor_penyebab' => 'nullable|string',
            'tindakan_solusi' => 'nullable|string',
            'student_id' => 'nullable|exists:students,id',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ]);

        $user = auth()->user();

        // Kalkulasi Analitik SLA Target Hours & Skor Prioritas (Analytical CRM Model)
        $slaTarget = match ($request->urgensi) {
            'darurat' => 12,
            'tinggi' => 24,
            'sedang' => 48,
            'rendah' => 72,
            default => 48,
        };

        $bobotUrgensi = match ($request->urgensi) {
            'darurat' => 40,
            'tinggi' => 30,
            'sedang' => 20,
            'rendah' => 10,
            default => 20,
        };

        $bobotKategori = match ($request->kategori) {
            'layanan_konseling', 'kedisiplinan' => 30,
            'fasilitas', 'akademik' => 25,
            default => 15,
        };

        $bobotPelapor = match ($user->role) {
            'guru', 'bk' => 30,
            'parent' => 25,
            'student' => 20,
            default => 25,
        };

        $skorPrioritas = min(100.00, (float) ($bobotUrgensi + $bobotKategori + $bobotPelapor));

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('tickets', 'public');
        }

        $prefix = 'TKT-' . date('Ym') . '-';
        $latestTicket = ComplaintTicket::where('ticket_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($latestTicket && preg_match('/-(\d+)$/', $latestTicket->ticket_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        $ticketNumber = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Fallback safety to guarantee absolute uniqueness
        while (ComplaintTicket::where('ticket_number', $ticketNumber)->exists()) {
            $nextNumber++;
            $ticketNumber = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        $ticket = ComplaintTicket::create([
            'ticket_number' => $ticketNumber,
            'user_id' => $user->id,
            'pelapor_role' => $user->role,
            'student_id' => $request->student_id,
            'kategori' => $request->kategori,
            'urgensi' => $request->urgensi,
            'sla_target_hours' => $slaTarget,
            'skor_prioritas' => $skorPrioritas,
            'status' => 'menunggu',
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'faktor_penyebab' => $request->faktor_penyebab,
            'tindakan_solusi' => $request->tindakan_solusi,
            'lampiran' => $lampiranPath,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', "Tiket {$ticketNumber} berhasil diajukan dengan skor prioritas analitik: {$skorPrioritas}.");
    }

    public function show(ComplaintTicket $ticket)
    {
        $user = auth()->user();

        // Otorisasi: Staf (Admin, Guru, BK) atau pemilik tiket
        if (!$user->isStaff() && $ticket->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat tiket ini.');
        }

        $ticket->load(['user', 'student', 'assignedStaff', 'responses.user']);

        return view('tickets.show', compact('ticket'));
    }

    public function respond(Request $request, ComplaintTicket $ticket)
    {
        $user = auth()->user();

        if (!$user->isStaff() && $ticket->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'pesan' => 'required|string',
            'status' => 'nullable|in:menunggu,diproses,selesai,ditolak',
            'faktor_penyebab' => 'nullable|string',
            'tindakan_solusi' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('responses', 'public');
        }

        ComplaintResponse::create([
            'complaint_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'pesan' => $request->pesan,
            'lampiran' => $lampiranPath,
            'is_internal_note' => $request->boolean('is_internal_note', false),
        ]);

        // Perbarui SLA tracking & analitik penyelesaian jika staf (Admin / Guru / BK) merespon
        if ($user->isStaff()) {
            if (!$ticket->first_responded_at) {
                $ticket->first_responded_at = Carbon::now();
            }
            if ($request->filled('status')) {
                $ticket->status = $request->status;
                if ($request->status === 'selesai' && !$ticket->resolved_at) {
                    $ticket->resolved_at = Carbon::now();
                }
            }
            if ($request->filled('faktor_penyebab')) {
                $ticket->faktor_penyebab = $request->faktor_penyebab;
            }
            if ($request->filled('tindakan_solusi')) {
                $ticket->tindakan_solusi = $request->tindakan_solusi;
            }
            if (!$ticket->assigned_to) {
                $ticket->assigned_to = $user->id;
            }
            $ticket->save();
        }

        return back()->with('success', 'Balasan respon tiket berhasil terkirim.');
    }

    public function rate(Request $request, ComplaintTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating_kepuasan' => 'required|integer|min:1|max:5',
            'ulasan_kepuasan' => 'nullable|string|max:500',
        ]);

        $ticket->update([
            'rating_kepuasan' => $request->rating_kepuasan,
            'ulasan_kepuasan' => $request->ulasan_kepuasan,
        ]);

        return back()->with('success', 'Terima kasih atas penilaian kepuasan layanan Anda.');
    }
}

