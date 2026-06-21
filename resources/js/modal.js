const peranLabels = {
    admin_pusat: 'Admin Pusat',
    admin_cabang: 'Admin Cabang',
    guru: 'Guru',
    pembina_ekstrakurikuler: 'Pembina Ekstrakurikuler',
};

const statusTahunAjaranLabels = {
    draft: 'Draft',
    aktif: 'Aktif',
    ditutup: 'Ditutup',
};

const statusSemesterLabels = {
    belum_dimulai: 'Belum Dimulai',
    berjalan: 'Berjalan',
    ditangguhkan: 'Ditangguhkan',
    selesai: 'Selesai',
};

const jenisSemesterLabels = {
    ganjil: 'Ganjil',
    genap: 'Genap',
};

const kelompokMapelLabels = {
    umum: 'Umum',
    kejuruan: 'Kejuruan',
    muatan_lokal: 'Muatan Lokal',
};

const jenisKelaminLabels = {
    L: 'Laki-laki',
    P: 'Perempuan',
};

const agamaLabels = {
    islam: 'Islam',
    kristen: 'Kristen',
    katolik: 'Katolik',
    hindu: 'Hindu',
    buddha: 'Buddha',
    konghucu: 'Konghucu',
    lainnya: 'Lainnya',
};

const siswaProfilFields = [
    'nis',
    'nisn',
    'nama',
    'tempat_lahir',
    'status_dalam_keluarga',
    'alamat',
    'kecamatan',
    'kabupaten_kota',
    'nama_ayah',
    'nama_ibu',
    'alamat_orang_tua',
    'nomor_telepon_rumah',
    'pekerjaan_ayah',
    'pekerjaan_ibu',
    'nama_wali_siswa',
    'alamat_wali_siswa',
    'nomor_telepon_wali_siswa',
    'pekerjaan_wali_siswa',
];

const setFormValue = (prefix, field, value) => {
    const element = document.getElementById(`${prefix}-${field}`);
    if (element) {
        element.value = value ?? '';
    }
};

const setAktifField = (prefix, aktif) => {
    const aktifCheckbox = document.getElementById(`${prefix}-aktif`);
    if (aktifCheckbox && aktifCheckbox.type === 'checkbox') {
        aktifCheckbox.checked = Boolean(aktif);
    } else if (aktifCheckbox) {
        aktifCheckbox.value = aktif ? 'Aktif' : 'Nonaktif';
    }
};

export const fillJurusanForm = (prefix, jurusan) => {
    setFormValue(prefix, 'kode', jurusan.kode);
    setFormValue(prefix, 'nama', jurusan.nama);
    setAktifField(prefix, jurusan.aktif);
};

export const fillMataPelajaranForm = (prefix, mapel) => {
    setFormValue(prefix, 'kode', mapel.kode);
    setFormValue(prefix, 'nama', mapel.nama);
    setAktifField(prefix, mapel.aktif);

    const kelompokSelect = document.getElementById(`${prefix}-kelompok`);
    if (kelompokSelect && kelompokSelect.tagName === 'SELECT') {
        kelompokSelect.value = mapel.kelompok ?? '';
    } else if (kelompokSelect) {
        kelompokSelect.value = kelompokMapelLabels[mapel.kelompok] ?? mapel.kelompok;
    }
};

export const fillKelasForm = (prefix, kelas) => {
    setFormValue(prefix, 'nama', kelas.nama);
    setFormValue(prefix, 'jurusan_nama', kelas.jurusan?.nama ?? '—');
    setFormValue(prefix, 'wali_kelas_nama', kelas.wali_kelas?.nama ?? '—');

    const jurusanSelect = document.getElementById(`${prefix}-jurusan_id`);
    if (jurusanSelect) {
        jurusanSelect.value = kelas.jurusan_id ?? '';
    }

    const tingkatSelect = document.getElementById(`${prefix}-tingkat`);
    if (tingkatSelect && tingkatSelect.tagName === 'SELECT') {
        tingkatSelect.value = kelas.tingkat ?? '';
    } else if (tingkatSelect) {
        tingkatSelect.value = kelas.tingkat ?? '';
    }

    const faseSelect = document.getElementById(`${prefix}-fase`);
    if (faseSelect && faseSelect.tagName === 'SELECT') {
        faseSelect.value = kelas.fase ?? '';
    } else if (faseSelect) {
        faseSelect.value = kelas.fase ?? '';
    }

    const waliSelect = document.getElementById(`${prefix}-wali_kelas_id`);
    if (waliSelect) {
        waliSelect.value = kelas.wali_kelas_id ?? '';
    }
};

export const fillSiswaForm = (prefix, siswa) => {
    const isReadonly = document.getElementById(`${prefix}-nis`)?.readOnly;
    const display = (value) => {
        if (!isReadonly) {
            return value ?? '';
        }

        return value === null || value === undefined || value === '' ? '—' : value;
    };

    siswaProfilFields.forEach((field) => {
        setFormValue(prefix, field, display(siswa[field]));
    });

    setFormValue(prefix, 'tanggal_lahir', isReadonly
        ? (siswa.tanggal_lahir ? siswa.tanggal_lahir.substring(0, 10) : '—')
        : (siswa.tanggal_lahir?.substring(0, 10) ?? ''));
    setFormValue(prefix, 'anak_ke', isReadonly
        ? (siswa.anak_ke ?? '—')
        : (siswa.anak_ke ?? ''));
    setAktifField(prefix, siswa.aktif);

    const jkSelect = document.getElementById(`${prefix}-jenis_kelamin`);
    if (jkSelect && jkSelect.tagName === 'SELECT') {
        jkSelect.value = siswa.jenis_kelamin ?? '';
    } else if (jkSelect) {
        jkSelect.value = siswa.jenis_kelamin
            ? (jenisKelaminLabels[siswa.jenis_kelamin] ?? siswa.jenis_kelamin)
            : '—';
    }

    const agamaField = document.getElementById(`${prefix}-agama`);
    if (agamaField && agamaField.tagName === 'SELECT') {
        agamaField.value = siswa.agama ?? '';
    } else if (agamaField) {
        agamaField.value = siswa.agama
            ? (agamaLabels[siswa.agama] ?? siswa.agama)
            : '—';
    }

    const kelasSaatIni = document.getElementById(`${prefix}-kelas_saat_ini`);
    if (kelasSaatIni) {
        const rombel = siswa.rombel_siswa?.[0] ?? siswa.rombelSiswa?.[0];
        kelasSaatIni.value = rombel?.kelas?.nama ?? '—';
    }
};

export const fillTahunAjaranForm = (prefix, tahunAjaran) => {
    const setValue = (field, value) => {
        const element = document.getElementById(`${prefix}-${field}`);
        if (element) {
            element.value = value ?? '';
        }
    };

    setValue('nama', tahunAjaran.nama);
    setValue('tanggal_mulai', tahunAjaran.tanggal_mulai?.substring(0, 10));
    setValue('status', statusTahunAjaranLabels[tahunAjaran.status] ?? tahunAjaran.status);
    setValue('tanggal_selesai', tahunAjaran.tanggal_selesai?.substring(0, 10) ?? '—');

    const semesterList = document.getElementById('detail-semester-list');
    if (semesterList && tahunAjaran.semester) {
        semesterList.innerHTML = tahunAjaran.semester.map((semester) => `
            <p><span class="font-medium">Semester ${jenisSemesterLabels[semester.jenis] ?? semester.jenis}:</span> ${statusSemesterLabels[semester.status] ?? semester.status}</p>
        `).join('');
    }

    const logList = document.getElementById('detail-log-list');
    if (logList) {
        const logs = tahunAjaran.log_akademik ?? [];
        if (logs.length === 0) {
            logList.innerHTML = '';
        } else {
            logList.innerHTML = `
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Riwayat</h3>
                <ul class="mt-2 space-y-2">
                    ${logs.map((log) => `
                        <li class="rounded-lg border border-slate-100 bg-slate-50 px-3 py-2 text-sm">
                            <p class="font-medium text-slate-900">${log.aksi?.replace(/_/g, ' ') ?? log.aksi}</p>
                            ${log.keterangan ? `<p class="text-slate-600">${log.keterangan}</p>` : ''}
                            <p class="text-xs text-slate-500">${log.pengguna?.nama ?? 'Sistem'}</p>
                        </li>
                    `).join('')}
                </ul>
            `;
        }
    }
};

export const fillKonfirmasiTahunAjaran = (konfirmasi) => {
    const form = document.getElementById('form-konfirmasi-tahun-ajaran');
    const judul = document.getElementById('konfirmasi-tahun-ajaran-judul');
    const pesan = document.getElementById('konfirmasi-tahun-ajaran-pesan');
    const submit = document.getElementById('konfirmasi-tahun-ajaran-submit');

    if (form) {
        form.action = konfirmasi.action ?? '';
    }
    if (judul) {
        judul.textContent = konfirmasi.judul ?? 'Konfirmasi';
    }
    if (pesan) {
        pesan.textContent = konfirmasi.pesan ?? '';
    }
    if (submit) {
        submit.textContent = konfirmasi.submitLabel ?? 'Lanjutkan';
        submit.className = `rounded-xl px-4 py-2 text-sm font-semibold text-white ${konfirmasi.submitClass ?? 'bg-emerald-600 hover:bg-emerald-700'}`;
    }
};

export const fillPenggunaForm = (prefix, pengguna) => {
    const setValue = (field, value) => {
        const element = document.getElementById(`${prefix}-${field}`);
        if (element) {
            element.value = value ?? '';
        }
    };

    setValue('username', pengguna.username);
    setValue('nama', pengguna.nama);
    setValue('email', pengguna.email);

    const cabangSelect = document.getElementById(`${prefix}-cabang_sekolah_id`);
    if (cabangSelect) {
        cabangSelect.value = pengguna.cabang_sekolah_id ?? '';
    }

    const cabangNama = document.getElementById(`${prefix}-cabang_sekolah_nama`);
    if (cabangNama) {
        cabangNama.value = pengguna.cabang_sekolah?.nama ?? '—';
    }

    const aktifCheckbox = document.getElementById(`${prefix}-aktif`);
    if (aktifCheckbox && aktifCheckbox.type === 'checkbox') {
        aktifCheckbox.checked = Boolean(pengguna.aktif);
    } else if (aktifCheckbox) {
        aktifCheckbox.value = pengguna.aktif ? 'Aktif' : 'Nonaktif';
    }

    const peranInput = document.getElementById(`${prefix}-peran`);
    if (peranInput) {
        peranInput.value = peranLabels[pengguna.peran] ?? pengguna.peran;
    }

    const passwordInput = document.getElementById(`${prefix}-password`);
    if (passwordInput) {
        passwordInput.value = '';
    }
};

export const fillCabangForm = (prefix, cabang) => {
    const setValue = (field, value) => {
        const element = document.getElementById(`${prefix}-${field}`);
        if (element) {
            element.value = value ?? '';
        }
    };

    setValue('kode', cabang.kode);
    setValue('nama', cabang.nama);
    setValue('kepala_sekolah', cabang.kepala_sekolah);
    setValue('alamat', cabang.alamat);
    setValue('telepon', cabang.telepon);

    const aktifCheckbox = document.getElementById(`${prefix}-aktif`);
    if (aktifCheckbox && aktifCheckbox.type === 'checkbox') {
        aktifCheckbox.checked = Boolean(cabang.aktif);
    } else if (aktifCheckbox) {
        aktifCheckbox.value = cabang.aktif ? 'Aktif' : 'Nonaktif';
    }

    const penggunaCount = document.getElementById(`${prefix}-pengguna_count`);
    if (penggunaCount) {
        penggunaCount.value = cabang.pengguna_count ?? 0;
    }
};

export const openModal = (modalId, data = null, tipe = null) => {
    const modal = document.getElementById(modalId);
    if (!modal) {
        return;
    }

    if (data) {
        if (modalId === 'modal-edit-cabang') {
            const form = document.getElementById('form-edit-cabang');
            if (form) {
                form.action = `/admin-pusat/cabang-sekolah/${data.route_key}`;
            }
            fillCabangForm('edit', data);
        }

        if (modalId === 'modal-detail-cabang') {
            fillCabangForm('detail', data);
        }

        if (modalId === 'modal-hapus-cabang') {
            const form = document.getElementById('form-hapus-cabang');
            const namaLabel = document.getElementById('hapus-cabang-nama');
            if (form) {
                form.action = `/admin-pusat/cabang-sekolah/${data.route_key}`;
            }
            if (namaLabel) {
                namaLabel.textContent = data.nama;
            }
        }

        if (modalId === 'modal-edit-pengguna') {
            const form = document.getElementById('form-edit-pengguna');
            if (form && tipe) {
                form.action = `/admin-pusat/pengguna/${tipe}/${data.route_key}`;
            }
            fillPenggunaForm('edit', data);
        }

        if (modalId === 'modal-detail-pengguna') {
            fillPenggunaForm('detail', data);
        }

        if (modalId === 'modal-hapus-pengguna') {
            const form = document.getElementById('form-hapus-pengguna');
            const namaLabel = document.getElementById('hapus-pengguna-nama');
            if (form && tipe) {
                form.action = `/admin-pusat/pengguna/${tipe}/${data.route_key}`;
            }
            if (namaLabel) {
                namaLabel.textContent = data.nama;
            }
        }

        if (modalId === 'modal-edit-tahun-ajaran') {
            const form = document.getElementById('form-edit-tahun-ajaran');
            if (form) {
                form.action = `/admin-cabang/data-akademik/tahun-ajaran/${data.route_key}`;
            }
            fillTahunAjaranForm('edit', data);
        }

        if (modalId === 'modal-detail-tahun-ajaran') {
            fillTahunAjaranForm('detail', data);
        }

        if (modalId === 'modal-hapus-tahun-ajaran') {
            const form = document.getElementById('form-hapus-tahun-ajaran');
            const namaLabel = document.getElementById('hapus-tahun-ajaran-nama');
            if (form) {
                form.action = `/admin-cabang/data-akademik/tahun-ajaran/${data.route_key}`;
            }
            if (namaLabel) {
                namaLabel.textContent = data.nama;
            }
        }

        if (modalId === 'modal-edit-jurusan') {
            document.getElementById('form-edit-jurusan').action = `/admin-cabang/data-akademik/jurusan/${data.route_key}`;
            fillJurusanForm('edit', data);
        }
        if (modalId === 'modal-detail-jurusan') fillJurusanForm('detail', data);
        if (modalId === 'modal-hapus-jurusan') {
            document.getElementById('form-hapus-jurusan').action = `/admin-cabang/data-akademik/jurusan/${data.route_key}`;
            document.getElementById('hapus-jurusan-nama').textContent = data.nama;
        }

        if (modalId === 'modal-edit-ekstrakurikuler') {
            document.getElementById('form-edit-ekstrakurikuler').action = `/admin-cabang/data-akademik/ekstrakurikuler/${data.route_key}`;
            fillJurusanForm('edit', data);
        }
        if (modalId === 'modal-detail-ekstrakurikuler') fillJurusanForm('detail', data);
        if (modalId === 'modal-hapus-ekstrakurikuler') {
            document.getElementById('form-hapus-ekstrakurikuler').action = `/admin-cabang/data-akademik/ekstrakurikuler/${data.route_key}`;
            document.getElementById('hapus-ekstrakurikuler-nama').textContent = data.nama;
        }

        if (modalId === 'modal-edit-pengguna-cabang' && tipe) {
            document.getElementById('form-edit-pengguna-cabang').action = `/admin-cabang/master-data/${tipe}/${data.route_key}`;
            fillPenggunaForm('edit', data);
        }
        if (modalId === 'modal-detail-pengguna-cabang') {
            fillPenggunaForm('detail', data);
        }
        if (modalId === 'modal-hapus-pengguna-cabang' && tipe) {
            document.getElementById('form-hapus-pengguna-cabang').action = `/admin-cabang/master-data/${tipe}/${data.route_key}`;
            document.getElementById('hapus-pengguna-cabang-nama').textContent = data.nama;
        }

        if (modalId === 'modal-edit-mata-pelajaran') {
            document.getElementById('form-edit-mata-pelajaran').action = `/admin-cabang/data-akademik/mata-pelajaran/${data.route_key}`;
            fillMataPelajaranForm('edit', data);
        }
        if (modalId === 'modal-detail-mata-pelajaran') fillMataPelajaranForm('detail', data);
        if (modalId === 'modal-hapus-mata-pelajaran') {
            document.getElementById('form-hapus-mata-pelajaran').action = `/admin-cabang/data-akademik/mata-pelajaran/${data.route_key}`;
            document.getElementById('hapus-mata-pelajaran-nama').textContent = data.nama;
        }

        if (modalId === 'modal-edit-kelas') {
            document.getElementById('form-edit-kelas').action = `/admin-cabang/data-akademik/kelas/${data.route_key}`;
            fillKelasForm('edit', data);
        }
        if (modalId === 'modal-detail-kelas') fillKelasForm('detail', data);
        if (modalId === 'modal-hapus-kelas') {
            document.getElementById('form-hapus-kelas').action = `/admin-cabang/data-akademik/kelas/${data.route_key}`;
            document.getElementById('hapus-kelas-nama').textContent = data.nama;
        }

        if (modalId === 'modal-edit-siswa') {
            document.getElementById('form-edit-siswa').action = `/admin-cabang/master-data/siswa/${data.route_key}`;
            fillSiswaForm('edit', data);
        }
        if (modalId === 'modal-detail-siswa') fillSiswaForm('detail', data);
        if (modalId === 'modal-hapus-siswa') {
            document.getElementById('form-hapus-siswa').action = `/admin-cabang/master-data/siswa/${data.route_key}`;
            document.getElementById('hapus-siswa-nama').textContent = data.nama;
        }
        if (modalId === 'modal-rombel-siswa') {
            document.getElementById('form-rombel-siswa').action = `/admin-cabang/master-data/siswa/${data.route_key}/rombel`;
            document.getElementById('rombel-siswa-nama').textContent = data.nama;
            const rombel = data.rombel_siswa?.[0];
            const kelasSelect = document.getElementById('rombel-kelas_id');
            if (kelasSelect && rombel) {
                kelasSelect.value = rombel.kelas_id ?? '';
            }
        }

        if (modalId === 'modal-edit-siswa-wali') {
            const form = document.querySelector('[data-form-edit-siswa-wali]');
            if (form) {
                const kelasId = form.dataset.kelasId;
                form.action = `/guru/wali-kelas/kelas/${kelasId}/siswa/${data.route_key}`;
            }
            fillSiswaForm('wali-edit', data);
        }
        if (modalId === 'modal-hapus-siswa-wali') {
            const form = document.querySelector('[data-form-hapus-siswa-wali]');
            const namaLabel = document.querySelector('[data-siswa-nama]');
            if (form) {
                const kelasId = form.dataset.kelasId;
                form.action = `/guru/wali-kelas/kelas/${kelasId}/siswa/${data.route_key}`;
            }
            if (namaLabel) {
                namaLabel.textContent = data.nama;
            }
        }
    }

    if (modalId === 'modal-konfirmasi-tahun-ajaran' && data) {
        fillKonfirmasiTahunAjaran(data);
    }

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
};

export const closeModal = (modal) => {
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');

    if (!document.querySelector('.modal:not(.hidden)')) {
        document.body.classList.remove('overflow-hidden');
    }
};

export const initModals = () => {
    document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const modalId = trigger.dataset.modalOpen;
            let data = null;
            const tipe = trigger.dataset.tipe ?? null;

            if (trigger.dataset.cabang) {
                data = JSON.parse(trigger.dataset.cabang);
            }

            if (trigger.dataset.pengguna) {
                data = JSON.parse(trigger.dataset.pengguna);
            }

            if (trigger.dataset.tahunAjaran) {
                data = JSON.parse(trigger.dataset.tahunAjaran);
            }

            if (trigger.dataset.konfirmasi) {
                data = JSON.parse(trigger.dataset.konfirmasi);
            }

            if (trigger.dataset.jurusan) {
                data = JSON.parse(trigger.dataset.jurusan);
            }

            if (trigger.dataset.mataPelajaran) {
                data = JSON.parse(trigger.dataset.mataPelajaran);
            }

            if (trigger.dataset.kelas) {
                data = JSON.parse(trigger.dataset.kelas);
            }

            if (trigger.dataset.siswa) {
                data = JSON.parse(trigger.dataset.siswa);
            }

            if (trigger.dataset.ekstrakurikuler) {
                data = JSON.parse(trigger.dataset.ekstrakurikuler);
            }

            if (trigger.dataset.penggunaCabang) {
                data = JSON.parse(trigger.dataset.penggunaCabang);
            }

            openModal(modalId, data, tipe);
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const modal = trigger.closest('.modal');
            if (modal) {
                closeModal(modal);
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal:not(.hidden)').forEach((modal) => {
                closeModal(modal);
            });
        }
    });
};
