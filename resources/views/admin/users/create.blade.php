<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Manajemen Akun</a>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Buat Akun Baru</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Tambah Akun Pengguna Portal</h1>
                <p class="text-xs text-slate-500 mt-0.5">Buat kredensial akun baru dan tautkan ke basis data siswa SMA Binaul Ummah.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-light text-xs">← Batal & Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="panel p-6 sm:p-8" x-data="{ selectedRole: '{{ old('role', 'parent') }}', newStudent: false }">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <!-- Pilihan Peran -->
                <div>
                    <label class="label-modern">Peran / Role Pengguna <span class="text-rose-500">*</span></label>
                    <select name="role" x-model="selectedRole" class="input-modern" required>
                        <option value="parent">Orang Tua / Wali Murid</option>
                        <option value="student">Siswa Aktif</option>
                        <option value="admin">Administrator / Manajemen Sekolah</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="label-modern">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso atau Ananda Rian" class="input-modern" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email Login -->
                <div>
                    <label class="label-modern">Email Login <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="user@domain.com" class="input-modern" required>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div>
                    <label class="label-modern">Kata Sandi Awal <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="input-modern" required>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Penautan Siswa (Bila Parent atau Student) -->
                <div x-show="selectedRole === 'parent' || selectedRole === 'student'" class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div>
                        <label class="label-modern" x-text="selectedRole === 'parent' ? 'Tautkan Siswa yang Didampingi' : 'Pilih Profil Siswa yang Bersangkutan'"></label>
                        <select name="student_id" class="input-modern">
                            <option value="">-- Pilih dari Daftar Siswa Terdaftar --</option>
                            @foreach ($students as $st)
                                <option value="{{ $st->id }}" {{ old('student_id') == $st->id ? 'selected' : '' }}>
                                    {{ $st->nama_lengkap }} (NIS: {{ $st->nis }} - Kelas: {{ $st->kelas }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">Akun ini akan langsung mendapatkan akses monitoring untuk siswa yang dipilih.</p>
                        <x-input-error :messages="$errors->get('student_id')" class="mt-1" />
                    </div>

                    <!-- Pilihan jika siswa belum ada di database (khusus siswa baru) -->
                    <div x-show="selectedRole === 'student'">
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="create_new_student" value="1" x-model="newStudent" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>Siswa belum ada di database? Buat profil siswa baru sekaligus</span>
                        </label>

                        <div x-show="newStudent" class="grid grid-cols-2 gap-3 mt-3 pt-3 border-t border-slate-200">
                            <div>
                                <label class="label-modern">NIS Baru</label>
                                <input type="text" name="nis" value="{{ old('nis') }}" placeholder="20241099" class="input-modern">
                                <x-input-error :messages="$errors->get('nis')" class="mt-1" />
                            </div>
                            <div>
                                <label class="label-modern">Kelas</label>
                                <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="X-MIPA-1" class="input-modern">
                                <x-input-error :messages="$errors->get('kelas')" class="mt-1" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.users.index') }}" class="btn-light text-xs">Batal</a>
                    <button type="submit" class="btn-indigo text-xs">Simpan & Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

