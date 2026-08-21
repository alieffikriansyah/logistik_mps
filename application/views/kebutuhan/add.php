<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Form Tambah Kebutuhan
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('kebutuhan') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-arrow-left"></i>
                    </span>
                    <span class="text">
                        Kembali
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?= $this->session->flashdata('pesan'); ?>
        
        <!-- TAMPILKAN ERROR VALIDASI JIKA ADA -->
        <?php if (validation_errors()): ?>
        <div class="alert alert-danger">
            <?= validation_errors(); ?>
        </div>
        <?php endif; ?>
        
        <form class="user" method="post" action="<?= base_url('kebutuhan/add') ?>">
            <!-- CSRF TOKEN - PENTING! -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            
            <div class="row form-group">
                <div class="col-lg-4">
                    <label>Pilih Barang *</label>
                    <select class="form-control" name="id_barang" id="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang as $b) : ?>
                            <option value="<?= $b['id_barang'] ?>" <?= set_select('id_barang', $b['id_barang']); ?>>
                                <?= $b['nama_barang'] ?> (<?= $b['nama_jenis'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label>Tanggal Kebutuhan *</label>
                    <input type="date" class="form-control" name="tanggal" 
                           value="<?= set_value('tanggal', date('Y-m-d')); ?>" required>
                </div>
                <div class="col-lg-4">
                    <label>Status *</label>
                    <select class="form-control" name="status" required>
                        <option value="1" <?= set_select('status', '1', true); ?>>Aktif</option>
                        <option value="0" <?= set_select('status', '0'); ?>>Non-Aktif</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-6">
                    <label>Jumlah Kebutuhan *</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="kebutuhan" 
                               placeholder="Masukkan jumlah kebutuhan" 
                               min="1" 
                               value="<?= set_value('kebutuhan'); ?>" 
                               required>
                        <div class="input-group-append">
                            <span class="input-group-text">Unit</span>
                        </div>
                    </div>
                    <small class="text-muted">Jumlah yang dibutuhkan</small>
                </div>
                <div class="col-lg-6">
                    <label>Informasi Barang Terpilih</label>
                    <div id="barang-info" class="alert alert-info">
                        <small><i class="fa fa-info-circle"></i> Pilih barang untuk melihat informasi stok</small>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon">
                            <i class="fa fa-save"></i>
                        </span>
                        <span class="text">
                            Simpan Kebutuhan
                        </span>
                    </button>
                    <button type="reset" class="btn btn-secondary btn-icon-split">
                        <span class="icon">
                            <i class="fa fa-undo"></i>
                        </span>
                        <span class="text">
                            Reset
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('id_barang');
    const barangInfo = document.getElementById('barang-info');
    
    barangSelect.addEventListener('change', function() {
        const selectedId = this.value;
        
        if (selectedId) {
            // Tampilkan loading
            barangInfo.innerHTML = '<small><i class="fa fa-spinner fa-spin"></i> Memuat informasi barang...</small>';
            
            // Fetch data barang menggunakan AJAX
            fetch(`<?= base_url('kebutuhan/get_stok_barang/') ?>${selectedId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Hitung persentase jika ada input kebutuhan
                        const kebutuhanInput = document.querySelector('input[name="kebutuhan"]');
                        const kebutuhan = kebutuhanInput ? parseFloat(kebutuhanInput.value) || 0 : 0;
                        const stok = parseFloat(data.data.stok);
                        const persentase = stok > 0 ? ((kebutuhan / stok) * 100) : 0;
                        
                        barangInfo.innerHTML = `
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="font-weight-bold">${data.data.nama_barang}</h6>
                                </div>
                                <div class="col-6">
                                    <small class="d-block"><strong>Stok Tersedia:</strong> ${data.data.stok.toLocaleString('id-ID')}</small>
                                    <small class="d-block"><strong>Satuan:</strong> ${data.data.nama_satuan}</small>
                                </div>
                                <div class="col-6">
                                    <small class="d-block"><strong>Persentase:</strong> ${persentase.toFixed(2)}%</small>
                                    <small class="text-muted">(${kebutuhan} ÷ ${data.data.stok}) × 100</small>
                                </div>
                            </div>
                        `;
                    } else {
                        barangInfo.innerHTML = '<small class="text-danger">Gagal memuat informasi barang</small>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    barangInfo.innerHTML = '<small class="text-danger">Error memuat informasi barang</small>';
                });
        } else {
            barangInfo.innerHTML = '<small><i class="fa fa-info-circle"></i> Pilih barang untuk melihat informasi</small>';
        }
    });
    
    // Trigger change jika ada nilai yang sudah dipilih
    if (barangSelect.value) {
        barangSelect.dispatchEvent(new Event('change'));
    }
    
    // Update persentase saat input kebutuhan berubah
    const kebutuhanInput = document.querySelector('input[name="kebutuhan"]');
    if (kebutuhanInput) {
        kebutuhanInput.addEventListener('input', function() {
            if (barangSelect.value) {
                barangSelect.dispatchEvent(new Event('change'));
            }
        });
    }
});
</script>