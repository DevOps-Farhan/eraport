@extends('layouts.guest')

@section('title', 'Masuk — eRaport')

@section('content')
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <img src="{{ asset('images/MK.png') }}" alt="Logo SMK Miftahul Khaer" class="mx-auto mb-0 h-24 w-24 object-contain">
                <h1 class="text-2xl font-semibold text-slate-900">eRaport</h1>
                <p class="mt-2 text-sm text-slate-500">SMKS Miftahul Khaer</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Username
                        </label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            placeholder="Masukan Username"
                            autofocus
                            required
                            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Password
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="Masukan Password"
                            required
                            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        >
                    </div>

                    <div class="flex items-center gap-3">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            value="1"
                            @checked(old('remember'))
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <label for="remember" class="text-sm text-slate-600">
                            Ingat saya
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    >
                        Masuk
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs text-slate-400">
                Copyright &copy; {{ date('Y') }} SMKS Miftahul Khaer.
            </p>
        </div>
    </div>
@endsection
