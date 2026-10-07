<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Layanan Informasi Sekolah</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Pengumuman & Edaran Resmi</h1>
            </div>
            <a href="{{ route('pengumuman.create') }}" class="btn-indigo text-xs">Buat Pengumuman Baru</a>
        </div>
    </x-slot>

    <div class="panel overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse ($pengumumans as $pengumuman)
                <article class="p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="max-w-3xl">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-indigo-600">{{ $pengumuman->tanggal_publish->translatedFormat('d F Y') }}</span>
                                <span class="badge {{ $pengumuman->target_audience === 'semua' ? 'bg-slate-100 text-slate-700' : ($pengumuman->target_audience === 'orang_tua' ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800') }}">
                                    Audience: {{ ucfirst(str_replace('_', ' ', $pengumuman->target_audience)) }}
                                </span>
                            </div>
                            <h2 class="mt-1.5 text-base font-bold text-slate-900">{{ $pengumuman->judul }}</h2>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">{{ $pengumuman->konten }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('pengumuman.edit', $pengumuman) }}" class="btn-light">Edit</a>
                            <form method="POST" action="{{ route('pengumuman.destroy', $pengumuman) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn bg-rose-600 text-white hover:bg-rose-700">Hapus</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="p-8 text-center text-sm text-slate-500">Belum ada pengumuman.</div>
            @endforelse
        </div>

        <div class="border-t border-emerald-100 p-5">
            {{ $pengumumans->links() }}
        </div>
    </div>
</x-app-layout>
