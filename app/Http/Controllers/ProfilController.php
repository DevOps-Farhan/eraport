<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profil\UpdatePasswordRequest;
use App\Http\Requests\Profil\UpdateProfilRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(): View
    {
        return view('profil.edit', [
            'pengguna' => auth()->user()->loadMissing('cabangSekolah'),
        ]);
    }

    public function update(UpdateProfilRequest $request): RedirectResponse
    {
        $request->user()->update([
            'nama' => $request->string('nama')->value(),
            'email' => $request->string('email')->toString() ?: null,
        ]);

        return redirect()
            ->route('profil.edit')
            ->with('sukses', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => $request->string('password')->value(),
        ]);

        $request->session()->regenerate();

        return redirect()
            ->route('profil.edit')
            ->with('sukses', 'Password berhasil diperbarui.');
    }
}
