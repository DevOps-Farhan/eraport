@extends(auth()->user()->layoutName())

@section('title', 'Akun Profil')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-slate-900">Akun Profil</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola informasi akun dan keamanan password Anda.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Informasi Profil</h2>
            <p class="mt-1 text-sm text-slate-500">Perbarui nama dan email akun Anda.</p>

            <form method="POST" action="{{ route('profil.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Username</label>
                    <input
                        type="text"
                        value="{{ $pengguna->username }}"
                        readonly
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-600"
                    >
                    <p class="mt-1 text-xs text-slate-400">Username tidak dapat diubah.</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Peran</label>
                    <input
                        type="text"
                        value="{{ $pengguna->peran->label() }}"
                        readonly
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-600"
                    >
                </div>

                @if ($pengguna->cabangSekolah)
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Cabang Sekolah</label>
                        <input
                            type="text"
                            value="{{ $pengguna->cabangSekolah->nama }}"
                            readonly
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-600"
                        >
                    </div>
                @endif

                <div>
                    <label for="nama" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input
                        id="nama"
                        name="nama"
                        type="text"
                        value="{{ old('nama', $pengguna->nama) }}"
                        required
                        class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $pengguna->email) }}"
                        class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Ubah Password</h2>
            <p class="mt-1 text-sm text-slate-500">Gunakan password yang kuat dan unik.</p>

            <form method="POST" action="{{ route('profil.password.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="password_lama" class="mb-1.5 block text-sm font-medium text-slate-700">Password Lama</label>
                    <input
                        id="password_lama"
                        name="password_lama"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password Baru</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-900"
                    >
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
