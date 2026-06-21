@extends('layouts.guru')

@section('title', 'Daftar Kelas (Wali Kelas)')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Kelola Data Kelas</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih kelas yang Anda ampu sebagai wali kelas.</p>
    </div>

    @if ($kelasList->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white px-6 py-10 text-center text-sm text-slate-600">
            Anda belum ditunjuk sebagai wali kelas di tahun ajaran aktif.
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($kelasList as $kelas)
                <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $kelas->nama }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $kelas->jurusan->nama }}</p>
                        <div class="mt-4 flex items-center gap-2 text-sm font-medium text-slate-600">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            {{ $kelas->jumlah_siswa }} Siswa Aktif
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('guru.wali-kelas.kelas.show', $kelas) }}" class="inline-flex w-full justify-center rounded-xl bg-sky-50 px-4 py-2.5 text-sm font-semibold text-sky-700 transition hover:bg-sky-100">
                            Kelola Data Kelas
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
