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

export const openModal = (modalId, cabang = null) => {
    const modal = document.getElementById(modalId);
    if (!modal) {
        return;
    }

    if (cabang) {
        if (modalId === 'modal-edit-cabang') {
            const form = document.getElementById('form-edit-cabang');
            if (form) {
                form.action = `/admin-pusat/cabang-sekolah/${cabang.id}`;
            }
            fillCabangForm('edit', cabang);
        }

        if (modalId === 'modal-detail-cabang') {
            fillCabangForm('detail', cabang);
        }

        if (modalId === 'modal-hapus-cabang') {
            const form = document.getElementById('form-hapus-cabang');
            const namaLabel = document.getElementById('hapus-cabang-nama');
            if (form) {
                form.action = `/admin-pusat/cabang-sekolah/${cabang.id}`;
            }
            if (namaLabel) {
                namaLabel.textContent = cabang.nama;
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
            let cabang = null;

            if (trigger.dataset.cabang) {
                cabang = JSON.parse(trigger.dataset.cabang);
            }

            openModal(modalId, cabang);
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
