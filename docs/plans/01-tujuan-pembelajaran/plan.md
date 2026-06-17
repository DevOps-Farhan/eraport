# Modul 01: Tujuan Pembelajaran

## Tujuan

Guru membuat tujuan pembelajaran (TP) untuk mata pelajaran dan kelas yang dia ampu melalui penugasan mengajar.

## Dependensi

- Fase 1: `penugasan_mengajar`, tahun ajaran aktif, semester berjalan
- Route placeholder: `guru.kelola-nilai.tujuan-pembelajaran`

## Skema Database

**Tabel `tujuan_pembelajaran`**

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| penugasan_mengajar_id | FK | Scoped ke guru + mapel + kelas |
| kode | string(20) | Mis. "3.1" |
| deskripsi_kompetensi | text | Deskripsi kompetensi TP |
| urutan | unsigned int | Urutan tampilan |
| timestamps | | |

Unique: `(penugasan_mengajar_id, kode)`

## Route

| Method | URI | Nama |
|--------|-----|------|
| GET | `/guru/kelola-nilai/tujuan-pembelajaran` | `guru.kelola-nilai.tujuan-pembelajaran.index` |
| POST | `/guru/kelola-nilai/tujuan-pembelajaran` | `guru.kelola-nilai.tujuan-pembelajaran.store` |
| PUT | `/guru/kelola-nilai/tujuan-pembelajaran/{tujuan_pembelajaran}` | `guru.kelola-nilai.tujuan-pembelajaran.update` |
| DELETE | `/guru/kelola-nilai/tujuan-pembelajaran/{tujuan_pembelajaran}` | `guru.kelola-nilai.tujuan-pembelajaran.destroy` |

Query param `penugasan` untuk filter penugasan mengajar aktif.

## Alur UI

1. Dropdown pilih penugasan mengajar (format: Mapel — Kelas)
2. Tabel TP: kode, deskripsi kompetensi, urutan
3. Modal tambah/edit: kode, deskripsi kompetensi, urutan
4. Tombol hapus dengan konfirmasi

## Aturan Bisnis

- Hanya penugasan mengajar milik guru login di tahun ajaran aktif
- Kode TP unik per penugasan mengajar
- Tidak bisa hapus TP jika sudah ada nilai TP terkait
- Edit/hapus diblokir jika semester berjalan status `Selesai` (read-only)

## File Deliverables

- `app/Models/TujuanPembelajaran.php`
- `app/Http/Controllers/Guru/TujuanPembelajaranController.php`
- `app/Http/Controllers/Guru/Concerns/MenanganiPenugasanGuru.php`
- `app/Http/Requests/Guru/StoreTujuanPembelajaranRequest.php`
- `app/Http/Requests/Guru/UpdateTujuanPembelajaranRequest.php`
- `resources/views/guru/kelola-nilai/tujuan-pembelajaran/`
- `database/factories/TujuanPembelajaranFactory.php`
- `tests/Feature/Guru/TujuanPembelajaranTest.php`

## Testing

- CRUD TP berhasil untuk penugasan milik guru
- Tolak akses penugasan guru lain (404)
- Tolak hapus TP yang sudah punya nilai
- Validasi kode unik per penugasan
