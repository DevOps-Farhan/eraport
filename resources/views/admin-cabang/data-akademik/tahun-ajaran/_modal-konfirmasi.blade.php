<div id="modal-konfirmasi-tahun-ajaran" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 id="konfirmasi-tahun-ajaran-judul" class="text-lg font-semibold text-slate-900"></h2>
            </div>
            <form id="form-konfirmasi-tahun-ajaran" method="POST" action="" class="px-6 py-4">
                @csrf
                <p id="konfirmasi-tahun-ajaran-pesan" class="text-sm text-slate-600"></p>
                <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                    <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                    <button
                        id="konfirmasi-tahun-ajaran-submit"
                        type="submit"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-white"
                    >
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
