<form method="POST" action="{{ $action }}" class="panel max-w-3xl p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-5">
        <div>
            <label for="judul" class="label-modern">Judul</label>
            <input id="judul" name="judul" value="{{ old('judul', $pengumuman?->judul) }}" class="input-modern" required>
            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="tanggal_publish" class="label-modern">Tanggal Publish</label>
                <input id="tanggal_publish" type="date" name="tanggal_publish" value="{{ old('tanggal_publish', $pengumuman?->tanggal_publish?->format('Y-m-d')) }}" class="input-modern" required>
                <x-input-error :messages="$errors->get('tanggal_publish')" class="mt-2" />
            </div>

            <div>
                <label for="target_audience" class="label-modern">Target Sasaran</label>
                <select id="target_audience" name="target_audience" class="input-modern" required>
                    <option value="semua" {{ old('target_audience', $pengumuman?->target_audience) === 'semua' ? 'selected' : '' }}>Seluruh Warga Sekolah</option>
                    <option value="orang_tua" {{ old('target_audience', $pengumuman?->target_audience) === 'orang_tua' ? 'selected' : '' }}>Khusus Orang Tua / Wali</option>
                    <option value="siswa" {{ old('target_audience', $pengumuman?->target_audience) === 'siswa' ? 'selected' : '' }}>Khusus Siswa</option>
                </select>
                <x-input-error :messages="$errors->get('target_audience')" class="mt-2" />
            </div>
        </div>

        <div>
            <label for="konten" class="label-modern">Konten</label>
            <textarea id="konten" name="konten" rows="8" class="input-modern" required>{{ old('konten', $pengumuman?->konten) }}</textarea>
            <x-input-error :messages="$errors->get('konten')" class="mt-2" />
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-3">
        <a href="{{ route('pengumuman.index') }}" class="btn-light">Batal</a>
        <button class="btn-primary">{{ $submit }}</button>
    </div>
</form>
