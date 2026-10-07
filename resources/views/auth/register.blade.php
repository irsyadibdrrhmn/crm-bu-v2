<x-guest-layout>
    <div class="mb-5">
        <h1 class="text-xl font-bold text-slate-900">Registrasi Akun Terverifikasi</h1>
        <p class="text-xs text-slate-500 mt-1">Buat akun akses portal layanan untuk Orang Tua / Wali atau Siswa aktif SMA Binaul Ummah.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Pilihan Peran Akun -->
        <div>
            <label class="label-modern">Jenis Akun Pendaftar</label>
            <div class="grid grid-cols-2 gap-3" x-data="{ role: 'parent' }">
                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50 text-xs font-semibold text-slate-700">
                    <input type="radio" name="role" value="parent" class="text-indigo-600 focus:ring-indigo-500" checked>
                    <span>Orang Tua / Wali</span>
                </label>
                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50 text-xs font-semibold text-slate-700">
                    <input type="radio" name="role" value="student" class="text-indigo-600 focus:ring-indigo-500">
                    <span>Siswa Aktif</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-1" />
        </div>

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="label-modern">Nama Lengkap</label>
            <input id="name" class="input-modern" type="text" name="name" value="{{ old('name') }}" placeholder="Nama sesuai identitas" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- NIS Siswa untuk Verifikasi Otomatis -->
        <div>
            <label for="nis" class="label-modern">
                Nomor Induk Siswa (NIS) Terdaftar <span class="text-indigo-600 font-normal">(Untuk tautan data)</span>
            </label>
            <input id="nis" class="input-modern" type="text" name="nis" value="{{ old('nis') }}" placeholder="Contoh: 20241001" required />
            <p class="text-[11px] text-slate-400 mt-1">Masukkan NIS aktif di SMA Binaul Ummah untuk menghubungkan akun secara otomatis.</p>
            <x-input-error :messages="$errors->get('nis')" class="mt-1" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="label-modern">Alamat Email Aktif</label>
            <input id="email" class="input-modern" type="email" name="email" value="{{ old('email') }}" placeholder="email@domain.com" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Kata Sandi -->
        <div>
            <label for="password" class="label-modern">Kata Sandi</label>
            <input id="password" class="input-modern" type="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div>
            <label for="password_confirmation" class="label-modern">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" class="input-modern" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <button type="submit" class="btn-indigo w-full py-2.5 text-sm font-semibold shadow-md mt-2">
            Daftarkan Akun
        </button>

        <div class="text-center pt-2">
            <a class="text-xs font-medium text-slate-500 hover:text-indigo-600 hover:underline" href="{{ route('login') }}">
                Sudah memiliki akun? Masuk di sini
            </a>
        </div>
    </form>
</x-guest-layout>
