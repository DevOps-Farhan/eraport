<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Raport {{ $siswa->nama }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 4px; }
        h2 { font-size: 13px; margin: 16px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; vertical-align: top; }
        th { background: #f1f5f9; font-weight: bold; }
        .meta td { border: none; padding: 2px 0; }
        .meta { margin-bottom: 16px; }
        .ttd { margin-top: 40px; width: 100%; }
        .ttd td { border: none; text-align: center; width: 50%; }
        .small { font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <h1>RAPOR SEMESTER {{ strtoupper($semester->jenis->label()) }}</h1>
    <p style="text-align:center;margin:0 0 16px;">{{ $cabang->nama }}</p>

    <table class="meta">
        <tr><td width="120">Nama</td><td>: {{ $siswa->nama }}</td></tr>
        <tr><td>NIS / NISN</td><td>: {{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</td></tr>
        <tr><td>Kelas</td><td>: {{ $kelas->nama }}</td></tr>
        <tr><td>Tahun Ajaran</td><td>: {{ $kelas->tahunAjaran->nama }}</td></tr>
    </table>

    <h2>A. Nilai Mata Pelajaran</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Rata TP</th>
                <th>Ujian</th>
                <th>Nilai Akhir</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilaiMapel as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item['mapel']->nama }}</td>
                    <td style="text-align:center">{{ $item['ringkasan']['rata_tp'] !== null ? number_format($item['ringkasan']['rata_tp'], 2) : '—' }}</td>
                    <td style="text-align:center">{{ $item['ringkasan']['nilai_ujian'] !== null ? number_format($item['ringkasan']['nilai_ujian'], 2) : '—' }}</td>
                    <td style="text-align:center">{{ $item['ringkasan']['nilai_akhir'] !== null ? number_format($item['ringkasan']['nilai_akhir'], 2) : '—' }}</td>
                    <td style="text-align:center">{{ $item['ringkasan']['predikat']?->value ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>B. Deskripsi Capaian Kompetensi</h2>
    @foreach ($nilaiMapel as $item)
        @if ($item['pencapaian'])
            <p><strong>{{ $item['mapel']->nama }}</strong></p>
            <p style="white-space:pre-line;margin:0 0 12px;">{{ $item['pencapaian'] }}</p>
        @endif
    @endforeach

    <h2>C. Ketidakhadiran</h2>
    <table>
        <tr>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Tanpa Keterangan</th>
        </tr>
        <tr>
            <td style="text-align:center">{{ $absensi?->sakit ?? 0 }}</td>
            <td style="text-align:center">{{ $absensi?->izin ?? 0 }}</td>
            <td style="text-align:center">{{ $absensi?->alpha ?? 0 }}</td>
        </tr>
    </table>

    <h2>D. Catatan Wali Kelas</h2>
    <p>{{ $catatan ?: '—' }}</p>

    <table class="ttd">
        <tr>
            <td>
                <p>Mengetahui,<br>Kepala Sekolah</p>
                <br><br><br>
                <p><strong>{{ $cabang->kepala_sekolah ?? '________________' }}</strong></p>
            </td>
            <td>
                <p>{{ $cabang->nama }}, {{ now()->translatedFormat('d F Y') }}<br>Wali Kelas</p>
                <br><br><br>
                <p><strong>{{ $waliKelas->nama }}</strong></p>
            </td>
        </tr>
    </table>
</body>
</html>
