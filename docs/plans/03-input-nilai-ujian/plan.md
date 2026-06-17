# Modul 03: Input Nilai Ujian

## Tujuan

Guru menginput nilai ujian per siswa per mata pelajaran dan kelas yang dia ampu.

## Dependensi

- Modul 02: nilai TP (untuk preview rata-rata TP)
- `NilaiRaportService` untuk formula nilai akhir

## Formula Nilai Akhir

```
Nilai Akhir = round(RataTP × 0.6 + NilaiUjian × 0.4, 2)
```

## Skema Database

**Tabel `nilai_ujian`**

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| penugasan_mengajar_id | FK | |
| siswa_id | FK | |
| semester_id | FK | |
| nilai | decimal(5,2) | 0–100 |
| timestamps | | |

Unique: `(penugasan_mengajar_id, siswa_id, semester_id)`

## Route

| Method | URI | Nama |
|--------|-----|------|
| GET | `/guru/kelola-nilai/nilai-ujian` | `guru.kelola-nilai.nilai-ujian.index` |
| GET | `/guru/kelola-nilai/nilai-ujian/{penugasan}` | `guru.kelola-nilai.nilai-ujian.show` |
| POST | `/guru/kelola-nilai/nilai-ujian/{penugasan}` | `guru.kelola-nilai.nilai-ujian.store` |

## Alur UI

1. Pilih penugasan mengajar
2. Tabel siswa rombel + kolom nilai ujian
3. Kolom read-only: rata-rata TP, preview nilai akhir
4. Simpan batch

## Aturan Bisnis

- Satu nilai ujian per siswa per penugasan per semester
- Rata TP dihitung dari TP yang sudah diisi (peringatan jika belum lengkap)
- Nilai akhir dihitung on-the-fly, tidak disimpan di tabel terpisah (MVP)

## File Deliverables

- `app/Models/NilaiUjian.php`
- `app/Services/NilaiRaportService.php`
- `app/Http/Controllers/Guru/NilaiUjianController.php`
- `app/Http/Requests/Guru/StoreNilaiUjianRequest.php`
- `resources/views/guru/kelola-nilai/nilai-ujian/`
- `tests/Feature/Guru/NilaiUjianTest.php`

## Testing

- Formula 60/40 benar
- Edge case: TP belum lengkap (rata dari yang ada)
- Validasi nilai 0–100
- Authorization penugasan
