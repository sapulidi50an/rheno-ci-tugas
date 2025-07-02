<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<!-- Notifikasi di luar container agar tidak tertutup -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mx-4 mb-3" role="alert" style="margin-top: 20px;">
        <strong><i class="fas fa-check-circle"></i> Berhasil!</strong> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mx-4 mb-3" role="alert" style="margin-top: 20px;">
        <strong><i class="fas fa-exclamation-triangle"></i> Error!</strong> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger alert-dismissible fade show mx-4 mb-3" role="alert" style="margin-top: 20px;">
        <div><strong><i class="fas fa-exclamation-triangle"></i> Validation Error: </strong></div>
        <?php foreach ($validation->getErrors() as $error): ?>
            <div style="margin-top: 5px;">• <?= $error ?></div>
        <?php endforeach; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Diskon</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nominal (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($diskon) && is_array($diskon)) : $no=1; foreach ($diskon as $d) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d/m/Y', strtotime($d['tanggal'])) ?></td>
                        <td>Rp <?= number_format($d['nominal'],0,',','.') ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success btn-sm btn-edit-diskon" 
                                    data-id="<?= $d['id'] ?>" 
                                    data-tanggal="<?= $d['tanggal'] ?>" 
                                    data-nominal="<?= $d['nominal'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    <i class="fas fa-edit"></i> Ubah
                                </button>
                                <form action="<?= base_url('diskon/delete/'.$d['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus data diskon ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            Tidak ada data diskon
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('diskon/store') ?>" method="post">
        <?= csrf_field(); ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahLabel">
            <i class="fas fa-plus-circle"></i> Tambah Data Diskon
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required value="<?= old('tanggal', date('Y-m-d')) ?>">
            <div class="form-text">Pilih tanggal berlaku diskon</div>
          </div>
          <div class="mb-3">
            <label for="nominal" class="form-label">Nominal Diskon (Rp) <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="nominal" name="nominal" required 
                   value="<?= old('nominal') ?>" min="1000" step="1000" placeholder="Contoh: 50000">
            <div class="form-text">Masukkan nominal diskon dalam rupiah (minimal Rp 1.000)</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditDiskon" method="post">
        <?= csrf_field(); ?>
        <input type="hidden" name="_method" value="PUT">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditLabel">
            <i class="fas fa-edit"></i> Edit Data Diskon
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
            <div class="form-text">Pilih tanggal berlaku diskon</div>
          </div>
          <div class="mb-3">
            <label for="edit_nominal" class="form-label">Nominal Diskon (Rp) <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="edit_nominal" name="nominal" required 
                   min="1000" step="1000" placeholder="Contoh: 50000">
            <div class="form-text">Masukkan nominal diskon dalam rupiah (minimal Rp 1.000)</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Custom CSS untuk memastikan notifikasi terlihat -->
<style>
/* Simplified Alert Styling */
.alert {
    position: relative !important;
    z-index: 9999 !important;
    border: 1px solid transparent;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 1rem;
}

.alert-success {
    color: #0f5132;
    background-color: #d1e7dd;
    border-color: #badbcc;
}

.alert-danger {
    color: #842029;
    background-color: #f8d7da;
    border-color: #f5c2c7;
}

.alert strong {
    margin-right: 8px;
}

.alert .fas {
    margin-right: 5px;
}

/* Animasi untuk notifikasi */
.alert.fade.show {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Styling tambahan untuk modal */
.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.text-danger {
    color: #dc3545 !important;
}
</style>

<script>
// Script untuk isi data ke modal edit
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btn-edit-diskon');
    const formEdit = document.getElementById('formEditDiskon');
    const inputTanggal = document.getElementById('edit_tanggal');
    const inputNominal = document.getElementById('edit_nominal');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const tanggal = this.getAttribute('data-tanggal');
            const nominal = this.getAttribute('data-nominal');
            
            inputTanggal.value = tanggal;
            inputNominal.value = nominal;
            formEdit.action = "<?= base_url('diskon/update/') ?>" + id;
        });
    });

    // Auto hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => {
                    alert.remove();
                }, 150);
            }
        }, 5000);
    });

    // Format number input
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove any non-digit characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
});

// Function to show custom alert
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <strong>${type === 'success' ? 'Berhasil!' : 'Error!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>

<?php
if (!$validation) {
    return redirect()->back()->withInput()->with('validation', $this->validator);
}
?>

<?= $this->endSection() ?>