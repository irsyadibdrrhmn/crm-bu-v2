<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(): View
    {
        $pengumumans = Pengumuman::with('admin')->latest('tanggal_publish')->paginate(10);

        return view('pengumuman.index', compact('pengumumans'));
    }

    public function create(): View
    {
        return view('pengumuman.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $request->user()->announcements()->create($data);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman sekolah berhasil dipublikasikan.');
    }

    public function edit(Pengumuman $pengumuman): View
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->update($this->validated($request));

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'target_audience' => ['required', 'in:semua,siswa,orang_tua'],
            'tanggal_publish' => ['required', 'date'],
        ]);
    }
}
