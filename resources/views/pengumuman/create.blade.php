<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Informasi Sekolah</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Buat Pengumuman</h1>
        </div>
    </x-slot>

    @include('pengumuman.partials.form', [
        'action' => route('pengumuman.store'),
        'method' => 'POST',
        'pengumuman' => null,
        'submit' => 'Simpan Pengumuman',
    ])
</x-app-layout>
