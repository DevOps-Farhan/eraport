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
        th { background: #ffffff; font-weight: bold; }
        .meta td { border: none; padding: 2px 0; }
        .meta { margin-bottom: 16px; }
        .ttd { margin-top: 40px; width: 100%; }
        .ttd td { border: none; text-align: center; width: 50%; }
        .small { font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <!-- 1. Sampul Depan -->
    <div style="page-break-after: always;">
        <div style="border: 2px solid #1e293b; text-align: center; padding: 10px 40px; height: 850px; box-sizing: border-box; position: relative;">
            <img src="{{ public_path('images/MK.png') }}" style="width: 120px; margin-bottom: 40px;">
            
            <p style="font-size: 14px; font-weight: bold; margin: 0 0 5px;">LAPORAN</p>
            <p style="font-size: 14px; font-weight: bold; margin: 0 0 5px;">CAPAIAN KOMPETENSI PESERTA DIDIK</p>
            <p style="font-size: 14px; font-weight: bold; margin: 0 0 5px;">SEKOLAH MENENGAH KEJURUAN</p>
            <p style="font-size: 14px; font-weight: bold; margin: 0 0 60px;">(SMK)</p>

            <div style="margin-top: 140px; margin-bottom: 140px;">
                <p style="font-size: 13px; margin-bottom: 15px;">Nama Peserta Didik :</p>
                <p style="font-size: 16px; font-weight: bold; margin-bottom: 40px;">{{ strtoupper($siswa->nama) }}</p>
                
                <p style="font-size: 13px; margin-bottom: 15px;">NIS / NISN :</p>
                <p style="font-size: 16px; font-weight: bold; margin-bottom: 60px;">{{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</p>
            </div>

            <div style="position: absolute; bottom: 80px; left: 0; right: 0;">
                <p style="font-size: 12px; font-weight: bold; margin: 0 0 5px;">KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN REPUBLIK INDONESIA</p>
                <p style="font-size: 12px; font-weight: bold; margin: 0;">PROVINSI {{ strtoupper($cabang->provinsi ?? 'BANTEN') }}</p>
            </div>
        </div>
    </div>

    <!-- 2. Identitas Sekolah -->
    <div style="page-break-after: always;">
        <div style="border: 2px solid #1e293b; padding: 120px 60px; height: 600px; box-sizing: border-box; position: relative;">
            <div style="text-align: center; margin-bottom: 80px;">
                <p style="font-size: 14px; font-weight: bold; margin: 0 0 5px;">LAPORAN</p>
                <p style="font-size: 14px; font-weight: bold; margin: 0 0 5px;">CAPAIAN KOMPETENSI PESERTA DIDIK</p>
                <p style="font-size: 14px; font-weight: bold; margin: 0 0 5px;">SEKOLAH MENENGAH KEJURUAN</p>
                <p style="font-size: 14px; font-weight: bold; margin: 0;">(SMK)</p>
            </div>

            <table class="meta" style="width: 100%; font-size: 13px; line-height: 2;">
                <tr><td width="180">Nama Sekolah</td><td>: {{ $cabang->nama }}</td></tr>
                <tr><td>NPSN/NSS</td><td>: {{ $cabang->npsn ?? '—' }} / {{ $cabang->nss ?? '—' }}</td></tr>
                <tr><td style="vertical-align: top;">Alamat Sekolah</td><td style="vertical-align: top;">: {{ $cabang->alamat ?? '—' }}</td></tr>
                <tr><td colspan="2" style="height: 20px;"></td></tr>
                <tr><td>Desa/Kelurahan</td><td>: {{ $cabang->desa_kelurahan ?? '—' }}</td></tr>
                <tr><td>Kecamatan</td><td>: {{ $cabang->kecamatan ?? '—' }}</td></tr>
                <tr><td>Kabupaten/Kota</td><td>: {{ $cabang->kabupaten_kota ?? '—' }}</td></tr>
                <tr><td>Provinsi</td><td>: {{ $cabang->provinsi ?? '—' }}</td></tr>
                <tr><td>Website</td><td>: @if($cabang->website) <a href="{{ $cabang->website }}" style="color: #2563eb; text-decoration: underline;">{{ $cabang->website }}</a> @else — @endif</td></tr>
                <tr><td>Email</td><td>: @if($cabang->email) <a href="mailto:{{ $cabang->email }}" style="color: #2563eb; text-decoration: underline;">{{ $cabang->email }}</a> @else — @endif</td></tr>
            </table>
        </div>
    </div>

    <!-- 3. Identitas Peserta Didik / Biodata Siswa -->
    <div style="page-break-after: always;">
        <div style="border: 2px solid #1e293b; padding: 20px 50px; min-height: 950px; box-sizing: border-box;">
            <h1 style="text-align: center; margin-bottom: 20px; font-size: 16px;">IDENTITAS PESERTA DIDIK</h1>
            <table class="meta" style="width: 100%; line-height: 1.5; font-size: 11px;">
                <tr><td width="220">1. Nama Peserta Didik (Lengkap)</td><td>: {{ $siswa->nama }}</td></tr>
                <tr><td>2. Nomor Induk Siswa</td><td>: {{ $siswa->nis }}</td></tr>
                <tr><td>3. Nomor Induk Siswa Nasional</td><td>: {{ $siswa->nisn ?? '—' }}</td></tr>
                <tr><td>4. Tempat, Tanggal Lahir</td><td>: {{ $siswa->tempat_lahir ?? '—' }}, {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->translatedFormat('d F Y') : '—' }}</td></tr>
                <tr><td>5. Jenis Kelamin</td><td>: {{ $siswa->jenis_kelamin ? $siswa->jenis_kelamin->value : '—' }}</td></tr>
                <tr><td>6. Agama</td><td>: {{ $siswa->agama ? $siswa->agama->value : '—' }}</td></tr>
                <tr><td>7. Status dalam Keluarga</td><td>: {{ $siswa->status_dalam_keluarga ?? '—' }}</td></tr>
                <tr><td>8. Anak ke</td><td>: {{ $siswa->anak_ke ?? '—' }}</td></tr>
                <tr><td style="vertical-align: top;">9. Alamat Peserta Didik</td><td style="vertical-align: top;">: {{ $siswa->alamat ?? '—' }}</td></tr>
                <tr><td>10. Nomor Telepon/Hp</td><td>: {{ $siswa->nomor_telepon_rumah ?? '—' }}</td></tr>
                <tr><td>11. Madrasah/Sekolah Asal</td><td>: —</td></tr>
                <tr><td>12. Diterima di Sekolah ini</td><td></td></tr>
                <tr><td style="padding-left: 20px;">Di kelas</td><td>: {{ $kelas->nama }}</td></tr>
                <tr><td style="padding-left: 20px;">Pada tanggal</td><td>: {{ now()->translatedFormat('d F Y') }}</td></tr>
                <tr><td style="vertical-align: top;">13. Nama Orang Tua</td><td></td></tr>
                <tr><td style="padding-left: 20px;">a. Ayah</td><td>: {{ $siswa->nama_ayah ?? '—' }}</td></tr>
                <tr><td style="padding-left: 20px;">b. Ibu</td><td>: {{ $siswa->nama_ibu ?? '—' }}</td></tr>
                <tr><td style="vertical-align: top;">14. Alamat Orang Tua</td><td style="vertical-align: top;">: {{ $siswa->alamat_orang_tua ?? '—' }}</td></tr>
                <tr><td>15. Nomor Telepon/Hp</td><td>: {{ $siswa->nomor_telepon_rumah ?? '—' }}</td></tr>
                <tr><td style="vertical-align: top;">16. Pekerjaan Orang Tua</td><td></td></tr>
                <tr><td style="padding-left: 20px;">a. Ayah</td><td>: {{ $siswa->pekerjaan_ayah ?? '—' }}</td></tr>
                <tr><td style="padding-left: 20px;">b. Ibu</td><td>: {{ $siswa->pekerjaan_ibu ?? '—' }}</td></tr>
                <tr><td>17. Nama Wali Peserta Didik</td><td>: {{ $siswa->nama_wali_siswa ?? '—' }}</td></tr>
                <tr><td style="vertical-align: top;">18. Alamat Wali Peserta Didik</td><td style="vertical-align: top;">: {{ $siswa->alamat_wali_siswa ?? '—' }}</td></tr>
                <tr><td style="padding-left: 20px;">Nomor Telepon Rumah</td><td>: {{ $siswa->nomor_telepon_wali_siswa ?? '—' }}</td></tr>
                <tr><td>19. Pekerjaan Wali Peserta Didik</td><td>: {{ $siswa->pekerjaan_wali_siswa ?? '—' }}</td></tr>
            </table>
            
            <div style="margin-top: 20px;">
                <table style="width: 100%; border: none; font-size: 11px;">
                    <tr>
                        <td style="width: 40%; border: none;">
                            <div style="width: 3cm; height: 4cm; border: 0px solid #000; text-align: center; line-height: 4cm; margin-left: 20px; font-size: 10px; color: #94a3b8;">
                                
                            </div>
                        </td>
                        <td style="width: 60%; border: none; text-align: left; vertical-align: bottom;">
                            <p style="margin: 0;">{{ $cabang->kabupaten_kota ?? $cabang->nama }}, {{ now()->translatedFormat('d F Y') }}<br>Kepala Sekolah</p>
                            <br><br><br><br>
                            <p style="margin: 0;"><strong>{{ $cabang->kepala_sekolah ?? '________________' }}</strong></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. Laporan Hasil Belajar -->
    <div style="page-break-after: always;">
        <table class="meta" style="width: 100%; font-size: 11px;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <table class="meta" style="width: 100%;">
                        <tr><td width="100">Nama</td><td>: {{ strtoupper($siswa->nama) }}</td></tr>
                        <tr><td>NIS / NISN</td><td>: {{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</td></tr>
                        <tr><td>Nama Sekolah</td><td>: {{ $cabang->nama }}</td></tr>
                        <tr><td>Alamat</td><td>: {{ $cabang->alamat ?? '—' }}</td></tr>
                    </table>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <table class="meta" style="width: 100%;">
                        <tr><td width="100">Kelas</td><td>: {{ $kelas->nama }}</td></tr>
                        <tr><td>Fase</td><td>: {{ $kelas->fase?->value ?? '—' }}</td></tr>
                        <tr><td>Semester</td><td>: {{ $semester->jenis->label() }}</td></tr>
                        <tr><td>Tahun Pelajaran</td><td>: {{ $kelas->tahunAjaran->nama }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <h1 style="font-size: 14px; margin-top: 20px; margin-bottom: 20px;">LAPORAN HASIL BELAJAR</h1>

        @php
            $groupedMapel = collect($nilaiMapel)->groupBy(function($item) {
                return $item['mapel']->kelompok->value ?? 'lainnya';
            });

            $kelompokLabels = [
                'umum' => 'Kelompok Mata Pelajaran Umum',
                'kejuruan' => 'Kelompok Mata Pelajaran Kejuruan',
                'muatan_lokal' => 'Kelompok Mata Pelajaran Muatan Lokal',
                'lainnya' => 'Kelompok Lainnya',
            ];
        @endphp

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 25%;">Mata Pelajaran</th>
                    <th style="width: 10%; text-align: center;">Nilai Akhir</th>
                    <th style="width: 60%;">Capaian Kompetensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach(['umum', 'kejuruan', 'muatan_lokal', 'lainnya'] as $kelompok)
                    @if($groupedMapel->has($kelompok))
                        <tr>
                            <td colspan="4" style="font-weight: bold; background-color: #f8fafc;">{{ $kelompokLabels[$kelompok] }}</td>
                        </tr>
                        @foreach($groupedMapel->get($kelompok) as $i => $item)
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td>{{ $item['mapel']->nama }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $item['ringkasan']['nilai_akhir'] !== null ? number_format($item['ringkasan']['nilai_akhir'], 0) : '—' }}</td>
                            <td style="white-space: pre-line; text-align: justify;">{{ $item['pencapaian'] ?: '—' }}</td>
                        </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
            <thead>
                <tr>
                    <th style="width: 100%; text-align: left;">Capaian Kokurikuler</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 12px; white-space: pre-line; text-align: justify;">{{ $kokurikuler ?: '—' }}</td>
                </tr>
            </tbody>
        </table>

    </div>

    <!-- 5. Laporan Ekstrakurikuler, Ketidakhadiran, & Catatan Wali Kelas -->
    <div style="page-break-after: always;">
        <table class="meta" style="width: 100%; font-size: 11px;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <table class="meta" style="width: 100%;">
                        <tr><td width="100">Nama</td><td>: {{ strtoupper($siswa->nama) }}</td></tr>
                        <tr><td>NIS / NISN</td><td>: {{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</td></tr>
                        <tr><td>Nama Sekolah</td><td>: {{ $cabang->nama }}</td></tr>
                        <tr><td>Alamat</td><td>: {{ $cabang->alamat ?? '—' }}</td></tr>
                    </table>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <table class="meta" style="width: 100%;">
                        <tr><td width="100">Kelas</td><td>: {{ $kelas->nama }}</td></tr>
                        <tr><td>Fase</td><td>: {{ $kelas->fase?->value ?? '—' }}</td></tr>
                        <tr><td>Semester</td><td>: {{ $semester->jenis->label() }}</td></tr>
                        <tr><td>Tahun Pelajaran</td><td>: {{ $kelas->tahunAjaran->nama }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 10px;">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No.</th>
                    <th style="width: 35%; text-align: center;">Ekstrakurikuler</th>
                    <th style="width: 60%; text-align: center;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilaiEkstrakurikuler as $i => $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $item->ekstrakurikuler->nama ?? '—' }}</td>
                    <td style="white-space: pre-line;">{{ $item->deskripsi ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td style="text-align: center;">1</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center;">2</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center;">3</td>
                    <td></td>
                    <td></td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <table style="width: 100%; border: none; margin-bottom: 10px;">
            <tr>
                <td style="width: 48%; border: none; padding: 0; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; margin: 0;">
                        <thead>
                            <tr>
                                <th colspan="3" style="text-align: center;">Ketidakhadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 50%;">Sakit</td>
                                <td style="width: 25%; text-align: center; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1;">{{ $absensi?->sakit ?? '-' }}</td>
                                <td style="width: 25%; text-align: center;">hari</td>
                            </tr>
                            <tr>
                                <td>Izin</td>
                                <td style="text-align: center; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1;">{{ $absensi?->izin ?? '-' }}</td>
                                <td style="text-align: center;">hari</td>
                            </tr>
                            <tr>
                                <td>Tanpa Keterangan</td>
                                <td style="text-align: center; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1;">{{ $absensi?->alpha ?? '-' }}</td>
                                <td style="text-align: center;">hari</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 4%; border: none; padding: 0;"></td>
                <td style="width: 48%; border: none; padding: 0; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; margin: 0;">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Catatan Wali Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="vertical-align: top; height: 53px;">{{ $catatan ?: '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr>
                    <th style="text-align: center;">Tanggapan Orang Tua/Wali Murid</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 60px;"></td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; text-align: center; vertical-align: top;">
                    <p style="margin: 0;"><br>Orang Tua Murid</p>
                    <br><br><br><br>
                    <p style="margin: 0; border-bottom: 1px solid #1e293b; display: inline-block; width: 150px;"></p>
                </td>
                <td style="width: 50%; border: none; text-align: center; vertical-align: top;">
                    <p style="margin: 0;">{{ $cabang->kabupaten_kota ?? $cabang->nama }}, {{ now()->translatedFormat('d F Y') }}<br>Wali Kelas</p>
                    <br><br><br><br>
                    <p style="margin: 0; border-bottom: 1px solid #1e293b; display: inline-block; width: 150px;"></p>
                    <p style="margin: 5px 0 0 0;"><strong>{{ $waliKelas->nama }}</strong></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: none; text-align: center; vertical-align: top; padding-top: 20px;">
                    <p style="margin: 0;">Mengetahui,<br>Kepala Sekolah</p>
                    <br><br><br><br>
                    <p style="margin: 0; border-bottom: 1px solid #1e293b; display: inline-block; width: 150px;"></p>
                    <p style="margin: 5px 0 0 0;"><strong>{{ $cabang->kepala_sekolah ?? '________________' }}</strong></p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
