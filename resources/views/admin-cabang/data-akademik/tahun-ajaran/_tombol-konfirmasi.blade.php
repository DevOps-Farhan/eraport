@props([
    'action',
    'judul',
    'pesan',
    'submitLabel',
    'submitClass' => 'bg-emerald-600 hover:bg-emerald-700',
    'label',
    'buttonClass' => '',
])

<button
    type="button"
    class="{{ $buttonClass }}"
    data-modal-open="modal-konfirmasi-tahun-ajaran"
    data-konfirmasi="{{ json_encode([
        'action' => $action,
        'judul' => $judul,
        'pesan' => $pesan,
        'submitLabel' => $submitLabel,
        'submitClass' => $submitClass,
    ]) }}"
>
    {{ $label }}
</button>
