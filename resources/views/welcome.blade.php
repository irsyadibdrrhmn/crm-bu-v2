<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PPDB SDIT Binaul Ummah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-emerald-50 text-slate-900">
    <main class="min-h-screen">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
            <a href="{{ route('welcome') }}" class="font-bold text-emerald-800">SDIT Binaul Ummah</a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-light">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-light">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth
            </div>
        </nav>

        <section class="mx-auto grid max-w-6xl gap-10 px-6 pb-16 pt-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-700">PPDB Online</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-extrabold leading-tight text-slate-950 sm:text-5xl">
                    Penerimaan Siswa Baru {{ $profil['nama_sekolah'] ?? 'SDIT Binaul Ummah' }}
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                    {{ $profil['deskripsi'] ?? 'Sistem informasi pendaftaran yang membantu orang tua mengirim data calon siswa, memantau status seleksi, dan membaca pengumuman resmi sekolah dengan mudah.' }}
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="btn-primary px-6 py-3">Daftar Sekarang</a>
                    <a href="{{ route('login') }}" class="btn-light px-6 py-3">Masuk ke Akun</a>
                </div>
            </div>

            <div class="panel overflow-hidden">
                <div class="bg-gradient-to-br from-emerald-600 to-sky-700 p-8 text-white">
                    <p class="text-sm font-semibold uppercase tracking-wider text-emerald-50">Keunggulan Sekolah</p>
                    <h2 class="mt-2 text-2xl font-bold">{{ $profil['tagline'] ?? 'Belajar, berkarakter, dan bertumbuh bersama.' }}</h2>
                </div>
                <div class="grid gap-4 p-6">
                    @php($programs = array_filter(explode("\n", $profil['program_unggulan'] ?? "Pendidikan Islami Terpadu\nKomunikasi PPDB Transparan\nProses Pendaftaran Lebih Rapi")))
                    @foreach ($programs as $program)
                        <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                            <h3 class="font-bold text-emerald-900">{{ $program }}</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">Program unggulan yang menjadi nilai tambah sekolah dalam mendampingi perkembangan siswa.</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-white py-14">
            <div class="mx-auto grid max-w-6xl gap-8 px-6 lg:grid-cols-2">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-700">Jadwal PPDB</p>
                    <h2 class="mt-3 text-3xl font-extrabold text-slate-950">Tahapan penerimaan siswa baru</h2>
                    <div class="mt-6 space-y-4">
                        @forelse ($jadwals as $jadwal)
                            <div class="rounded-lg border border-emerald-100 p-4">
                                <p class="font-bold text-slate-900">{{ $jadwal->tahapan }}</p>
                                <p class="mt-1 text-sm font-semibold text-emerald-700">
                                    {{ $jadwal->tanggal_mulai->format('d/m/Y') }}
                                    @if ($jadwal->tanggal_selesai)
                                        - {{ $jadwal->tanggal_selesai->format('d/m/Y') }}
                                    @endif
                                </p>
                                <p class="mt-1 text-sm leading-6 text-slate-600">{{ $jadwal->keterangan }}</p>
                            </div>
                        @empty
                            <p class="text-slate-600">Jadwal PPDB akan diumumkan segera.</p>
                        @endforelse
                    </div>
                </div>

                <form method="POST" action="{{ route('leads.store') }}" class="panel p-6">
                    @csrf
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-700">Saya Berminat</p>
                    <h2 class="mt-3 text-2xl font-extrabold text-slate-950">Minta informasi PPDB</h2>
                    @if (session('lead_success'))
                        <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('lead_success') }}</div>
                    @endif
                    <div class="mt-5 grid gap-4">
                        <div><label class="label-modern">Nama Orang Tua</label><input name="nama_orang_tua" value="{{ old('nama_orang_tua') }}" class="input-modern" required></div>
                        <div><label class="label-modern">No HP/WhatsApp</label><input name="no_hp" value="{{ old('no_hp') }}" class="input-modern" required></div>
                        <div><label class="label-modern">Nama Anak</label><input name="nama_anak" value="{{ old('nama_anak') }}" class="input-modern"></div>
                        <div><label class="label-modern">Sumber Informasi</label><input name="sumber_informasi" value="{{ old('sumber_informasi') }}" class="input-modern" placeholder="Instagram, brosur, teman, dll."></div>
                        <div><label class="label-modern">Pertanyaan/Catatan</label><textarea name="catatan" rows="4" class="input-modern">{{ old('catatan') }}</textarea></div>
                        <button class="btn-primary">Kirim Minat</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="mx-auto grid max-w-6xl gap-8 px-6 py-14 lg:grid-cols-2">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-700">Profil</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-950">Visi dan Misi Sekolah</h2>
                <div class="mt-6 grid gap-4">
                    <div class="panel p-5"><h3 class="font-bold text-emerald-900">Visi</h3><p class="mt-2 text-sm leading-6 text-slate-600">{{ $profil['visi'] ?? 'Menjadi sekolah dasar Islam terpadu yang unggul dalam akhlak, ilmu, dan kemandirian.' }}</p></div>
                    <div class="panel p-5"><h3 class="font-bold text-emerald-900">Misi</h3><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $profil['misi'] ?? 'Menyelenggarakan pembelajaran yang aktif, Islami, dan ramah anak.' }}</p></div>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-700">Pengumuman</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-950">Informasi terbaru</h2>
                <div class="mt-6 space-y-4">
                    @forelse ($pengumumans as $pengumuman)
                        <article class="panel p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">{{ $pengumuman->tanggal_publish->format('d/m/Y') }}</p>
                            <h3 class="mt-1 font-bold text-slate-900">{{ $pengumuman->judul }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ Str::limit($pengumuman->konten, 160) }}</p>
                        </article>
                    @empty
                        <p class="text-slate-600">Belum ada pengumuman terbaru.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
</body>
</html>
