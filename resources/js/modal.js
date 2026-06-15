const peranLabels = {
    admin_pusat: 'Admin Pusat',
    admin_cabang: 'Admin Cabang',
    guru: 'Guru',
    pembina_ekstrakurikuler: 'Pembina Ekstrakurikuler',
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
                form.action = `/admin-pusat/cabang-sekolah/${data.id}`;
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
                form.action = `/admin-pusat/cabang-sekolah/${data.id}`;
            }
            if (namaLabel) {
                namaLabel.textContent = data.nama;
            }
        }

        if (modalId === 'modal-edit-pengguna') {
            const form = document.getElementById('form-edit-pengguna');
            if (form && tipe) {
                form.action = `/admin-pusat/pengguna/${tipe}/${data.id}`;
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
                form.action = `/admin-pusat/pengguna/${tipe}/${data.id}`;
            }
            if (namaLabel) {
                namaLabel.textContent = data.nama;
            }
        }
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
