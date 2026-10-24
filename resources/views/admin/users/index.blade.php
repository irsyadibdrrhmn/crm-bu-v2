<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Administrasi Pengguna</span>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Akses Portal</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Manajemen Akun Orang Tua & Siswa</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola kredensial akun, otorisasi hak akses, dan tautan relasi siswa dengan wali murid.</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn-indigo text-xs">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    + Tambah Akun Pengguna
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter bar -->
        <div class="panel p-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="label-modern">Cari Nama / Email</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="input-modern">
                </div>
                <div>
                    <label class="label-modern">Filter Peran</label>
                    <select name="role" class="input-modern">
                        <option value="">Semua Peran</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin & Manajemen</option>
                        <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru Mata Pelajaran / Wali Kelas</option>
                        <option value="bk" {{ request('role') === 'bk' ? 'selected' : '' }}>Guru BP/BK (Konselor)</option>
                        <option value="parent" {{ request('role') === 'parent' ? 'selected' : '' }}>Orang Tua / Wali</option>
                        <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary w-full text-xs">Filter Akun</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-light text-xs">Reset</a>
                </div>
            </form>
        </div>

        <!-- Tabel Pengguna -->
        <div class="panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Nama Pengguna</th>
                            <th>Email Login</th>
                            <th>Peran / Role</th>
                            <th>Keterkaitan Data Siswa</th>
                            <th>Terdaftar Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $u)
                            <tr>
                                <td>
                                    <div class="text-xs font-bold text-slate-900">{{ $u->name }}</div>
                                    @if ($u->id === auth()->id())
                                        <span class="text-[10px] text-indigo-600 font-semibold">(Akun Anda)</span>
                                    @endif
                                </td>
                                <td class="font-mono text-xs text-slate-600">{{ $u->email }}</td>
                                <td>
                                    @php
                                        $badgeClasses = match($u->role) {
                                            'admin' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                            'guru' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                            'bk' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                            'parent' => 'bg-sky-100 text-sky-800 border border-sky-200',
                                            'student' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                            default => 'bg-slate-100 text-slate-800'
                                        };
                                        $roleName = match($u->role) {
                                            'admin' => 'Manajemen Sekolah',
                                            'guru' => 'Guru / Wali Kelas',
                                            'bk' => 'Guru BP / BK',
                                            'parent' => 'Orang Tua / Wali',
                                            'student' => 'Siswa Aktif',
                                            default => ucfirst($u->role)
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClasses }}">
                                        {{ $roleName }}
                                    </span>
                                </td>
                                <td>
                                    @if ($u->role === 'guru' || $u->role === 'bk')
                                        <div class="text-xs text-slate-800 font-semibold">{{ $u->jabatan ?? ($u->role === 'bk' ? 'Guru Konseling BP/BK' : 'Guru Pengampu') }}</div>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $u->nip ? 'NIP: ' . $u->nip : 'Pendidik Terdaftar' }}</span>
                                    @elseif ($u->role === 'parent')
                                        @if ($u->children->count())
                                            <div class="text-xs text-slate-800 font-semibold">
                                                {{ $u->children->pluck('nama_lengkap')->join(', ') }}
                                            </div>
                                            <span class="text-[10px] text-slate-400">({{ $u->children->count() }} siswa terhubung)</span>
                                        @else
                                            <span class="text-xs text-amber-600 font-medium">Belum ditautkan siswa</span>
                                        @endif
                                    @elseif ($u->role === 'student')
                                        @if ($u->studentProfile)
                                            <div class="text-xs text-slate-800 font-semibold">
                                                NIS: {{ $u->studentProfile->nis }} ({{ $u->studentProfile->kelas }})
                                            </div>
                                        @else
                                            <span class="text-xs text-amber-600 font-medium">Profil siswa belum lengkap</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400">Akses Administratif Penuh</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $u->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $u) }}" class="btn-light !py-1 !px-2.5 !text-xs font-semibold hover:text-indigo-600">
                                            Edit
                                        </a>
                                        @if ($u->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-light !py-1 !px-2.5 !text-xs text-rose-600 hover:bg-rose-50 hover:border-rose-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-xs text-slate-400">Tidak ada akun yang sesuai kriteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

