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
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        } else {
            // Filter tambahan untuk Admin
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('urgensi')) {
                $query->where('urgensi', $request->urgensi);
            }
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }
        }

        $tickets = $query->paginate(15)->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $students = collect();

        if ($user->isParent()) {
            $students = $user->children;
        } elseif ($user->isStudent() && $user->studentProfile) {
            $students = collect([$user->studentProfile]);
        } elseif ($user->isAdmin()) {
            $students = Student::orderBy('nama_lengkap')->get();
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
            'student_id' => 'nullable|exists:students,id',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ]);

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
            'user_id' => auth()->id(),
            'student_id' => $request->student_id,
            'kategori' => $request->kategori,
            'urgensi' => $request->urgensi,
            'status' => 'menunggu',
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lampiran' => $lampiranPath,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', "Tiket {$ticketNumber} berhasil diajukan.");
    }

    public function show(ComplaintTicket $ticket)
    {
        $user = auth()->user();

        // Otorisasi sederhana: Admin atau pemilik tiket
        if (!$user->isAdmin() && $ticket->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat tiket ini.');
        }

        $ticket->load(['user', 'student', 'assignedStaff', 'responses.user']);

        return view('tickets.show', compact('ticket'));
    }

    public function respond(Request $request, ComplaintTicket $ticket)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $ticket->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'pesan' => 'required|string',
            'status' => 'nullable|in:menunggu,diproses,selesai,ditolak',
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

        // Perbarui SLA tracking jika admin merespon
        if ($user->isAdmin()) {
            if (!$ticket->first_responded_at) {
                $ticket->first_responded_at = Carbon::now();
            }
            if ($request->filled('status')) {
                $ticket->status = $request->status;
                if ($request->status === 'selesai' && !$ticket->resolved_at) {
                    $ticket->resolved_at = Carbon::now();
                }
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

