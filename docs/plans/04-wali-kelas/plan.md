# Modul 04: Wali Kelas

## Tujuan

Guru yang ditunjuk sebagai wali kelas mengelola data siswa kelasnya, rekap absensi, catatan akhir semester, serta mencetak leger nilai dan raport.

## Dependensi

- Fase 1: `kelas.wali_kelas_id`, `rombel_siswa`, `siswa`
- Modul 02–03: nilai TP, nilai ujian, `NilaiRaportService`
- Package: `barryvdh/laravel-dompdf` untuk cetak PDF

## Skema Database

**Tabel `absensi_siswa`**

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| siswa_id | FK | |
| semester_id | FK | |
| sakit | unsigned int | Default 0 |
| izin | unsigned int | Default 0 |
| alpha | unsigned int | Default 0 |
| timestamps | | |

Unique: `(siswa_id, semester_id)`

**Tabel `catatan_wali_kelas`**

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| siswa_id | FK | |
| semester_id | FK | |
| catatan | text | Catatan akhir semester |
| timestamps | | |

Unique: `(siswa_id, semester_id)`

## Sub-modul 4A: Kelola Data Kelas

**Route:** `guru/wali-kelas/kelas`

- Deteksi kelas: `Kelas::where('wali_kelas_id', auth()->id())` di tahun ajaran aktif
- Bukan wali kelas → pesan informatif (bukan 403)
- Tambah siswa baru + langsung masuk rombel kelas wali
- Edit identitas siswa di kelas
- Hapus dari kelas (nonaktifkan/delete rombel)

## Sub-modul 4B: Rekap Absensi & Catatan

**Route:** `guru/wali-kelas/absensi-catatan`

- Tabel siswa kelas wali
- Input sakit, izin, alpha per siswa
- Textarea catatan akhir semester
- Simpan batch per semester berjalan

## Sub-modul 4C: Leger & Cetak Raport

**Route:** `guru/wali-kelas/leger-raport`

- Leger: baris = siswa, kolom = mapel (dari penugasan mengajar kelas)
- Nilai akhir per sel dari `NilaiRaportService`
- Cetak raport per siswa → PDF

**Isi raport PDF:**
- Identitas siswa & sekolah
- Nilai per mapel: rata TP, nilai ujian, nilai akhir, predikat
- Deskripsi pencapaian per TP
- Absensi (S/I/A)
- Catatan wali kelas
- Tanda tangan wali kelas & kepala sekolah

## Route

| Method | URI | Nama |
|--------|-----|------|
| GET | `/guru/wali-kelas/kelas` | `guru.wali-kelas.kelas.index` |
| POST | `/guru/wali-kelas/kelas/siswa` | `guru.wali-kelas.kelas.store-siswa` |
| PUT | `/guru/wali-kelas/kelas/siswa/{siswa}` | `guru.wali-kelas.kelas.update-siswa` |
| DELETE | `/guru/wali-kelas/kelas/siswa/{siswa}` | `guru.wali-kelas.kelas.destroy-siswa` |
| GET | `/guru/wali-kelas/absensi-catatan` | `guru.wali-kelas.absensi-catatan.index` |
| POST | `/guru/wali-kelas/absensi-catatan` | `guru.wali-kelas.absensi-catatan.store` |
| GET | `/guru/wali-kelas/leger-raport` | `guru.wali-kelas.leger-raport.index` |
| GET | `/guru/wali-kelas/leger-raport/{siswa}/cetak` | `guru.wali-kelas.leger-raport.cetak` |

## File Deliverables

- `app/Models/AbsensiSiswa.php`
- `app/Models/CatatanWaliKelas.php`
- `app/Http/Controllers/Guru/Concerns/MenanganiWaliKelas.php`
- `app/Http/Controllers/Guru/WaliKelas/KelasController.php`
- `app/Http/Controllers/Guru/WaliKelas/AbsensiCatatanController.php`
- `app/Http/Controllers/Guru/WaliKelas/LegerRaportController.php`
- `resources/views/guru/wali-kelas/`
- `tests/Feature/Guru/WaliKelasTest.php`

## Testing

- Hanya wali kelas kelas X yang bisa akses
- Create siswa + rombel berhasil
- Absensi & catatan batch save
- Cetak PDF mengembalikan 200
- Guru bukan wali kelas melihat pesan informatif
