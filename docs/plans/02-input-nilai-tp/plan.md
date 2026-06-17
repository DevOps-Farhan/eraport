# Modul 02: Input Nilai Tujuan Pembelajaran

## Tujuan

Guru menginput nilai capaian per TP per siswa. Sistem auto-generate `deskripsi_pencapaian` dari deskripsi kompetensi TP dengan penyesuaian kata kerja berdasarkan predikat.

## Dependensi

- Modul 01: `tujuan_pembelajaran`
- Fase 1: `rombel_siswa`, semester berjalan

## Skema Database

**Tabel `nilai_tujuan_pembelajaran`**

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| tujuan_pembelajaran_id | FK | |
| siswa_id | FK | |
| semester_id | FK | |
| nilai | decimal(5,2) | 0–100 |
| predikat | string | SB, B, C, D |
| deskripsi_pencapaian | text | Auto-generated |
| timestamps | | |

Unique: `(tujuan_pembelajaran_id, siswa_id, semester_id)`

## Predikat

| Predikat | Rentang | Label |
|----------|---------|-------|
| SB | ≥90 | Sangat Baik |
| B | 80–89 | Baik |
| C | 70–79 | Cukup |
| D | <70 | Kurang |

## Auto-generate Deskripsi

Ambil `deskripsi_kompetensi` dari TP, sesuaikan kata kerja:

| Predikat | Transformasi |
|----------|--------------|
| SB | "mampu" → "sangat mampu" |
| B | tidak diubah |
| C | "mampu" → "cukup mampu" |
| D | "mampu" → "belum mampu" |

Kata kerja yang didukung: mampu, memahami, menjelaskan, menganalisis, menerapkan, mendemonstrasikan.

## Route

| Method | URI | Nama |
|--------|-----|------|
| GET | `/guru/kelola-nilai/input-nilai-tp` | `guru.kelola-nilai.input-nilai-tp.index` |
| GET | `/guru/kelola-nilai/input-nilai-tp/{penugasan}` | `guru.kelola-nilai.input-nilai-tp.show` |
| POST | `/guru/kelola-nilai/input-nilai-tp/{penugasan}` | `guru.kelola-nilai.input-nilai-tp.store` |

## Alur UI

1. Pilih penugasan mengajar
2. Grid siswa (baris) × TP (kolom)
3. Input nilai 0–100 per sel
4. Simpan batch (bulk upsert)

## File Deliverables

- `app/Models/NilaiTujuanPembelajaran.php`
- `app/Enums/PredikatCapaian.php`
- `app/Services/DeskripsiPencapaianService.php`
- `app/Http/Controllers/Guru/NilaiTpController.php`
- `app/Http/Requests/Guru/StoreNilaiTpRequest.php`
- `resources/views/guru/kelola-nilai/input-nilai-tp/`
- `tests/Feature/Guru/NilaiTpTest.php`

## Testing

- Bulk save nilai berhasil
- Predikat dan deskripsi auto-generate benar
- Validasi rentang nilai 0–100
- Tolak akses penugasan orang lain
