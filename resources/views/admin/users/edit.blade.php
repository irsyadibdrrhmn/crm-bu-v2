<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Manajemen Akun</a>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Edit Akun</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Edit Akun Pengguna: {{ $user->name }}</h1>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-light text-xs">← Batal & Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="panel p-6 sm:p-8" x-data="{ selectedRole: '{{ old('role', $user->role) }}' }">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Pilihan Peran -->
                <div>
                    <label class="label-modern">Peran / Role Pengguna <span class="text-rose-500">*</span></label>
                    <select name="role" x-model="selectedRole" class="input-modern" required>
                        <option value="parent">Orang Tua / Wali Murid</option>
                        <option value="student">Siswa Aktif</option>
                        <option value="guru">Guru Mata Pelajaran / Wali Kelas</option>
                        <option value="bk">Guru Bimbingan Konseling (BP/BK)</option>
                        <option value="admin">Administrator / Manajemen Sekolah</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <!-- Kolom Tambahan Khusus Guru / BP/BK / Admin -->
                <div x-show="selectedRole === 'guru' || selectedRole === 'bk' || selectedRole === 'admin'" class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-xl bg-indigo-50/50 border border-indigo-100">
                    <div>
                        <label class="label-modern">NIP / NUPTK (Opsional)</label>
                        <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" placeholder="19840215..." class="input-modern">
                        <x-input-error :messages="$errors->get('nip')" class="mt-1" />
                    </div>
                    <div>
                        <label class="label-modern">Jabatan / Tugas</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" placeholder="Wali Kelas / Koordinator BK" class="input-modern">
                        <x-input-error :messages="$errors->get('jabatan')" class="mt-1" />
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="label-modern">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-modern" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email Login -->
                <div>
                    <label class="label-modern">Email Login <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-modern" required>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Reset Password (Opsional) -->
                <div>
                    <label class="label-modern">Ubah Kata Sandi (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password" placeholder="••••••••" class="input-modern">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Tautkan Siswa -->
                <div x-show="selectedRole === 'parent' || selectedRole === 'student'" class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <label class="label-modern" x-text="selectedRole === 'parent' ? 'Tautkan Siswa yang Didampingi' : 'Tautkan Profil Siswa'"></label>
                    @php
                        $currentStudentId = $user->role === 'parent' ? $user->children->first()?->id : $user->studentProfile?->id;
                    @endphp
                    <select name="student_id" class="input-modern">
                        <option value="">-- Tidak Ditautkan / Biarkan --</option>
                        @foreach ($students as $st)
                            <option value="{{ $st->id }}" {{ old('student_id', $currentStudentId) == $st->id ? 'selected' : '' }}>
                                {{ $st->nama_lengkap }} (NIS: {{ $st->nis }} - Kelas: {{ $st->kelas }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('student_id')" class="mt-1" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.users.index') }}" class="btn-light text-xs">Batal</a>
                    <button type="submit" class="btn-indigo text-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

