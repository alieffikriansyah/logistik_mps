<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Form Edit Kebutuhan
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
        
        <form class="user" method="post" action="<?= base_url('kebutuhan/edit/') . $kebutuhan['id_kebutuhan'] ?>">
            <!-- CSRF TOKEN -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            
            <div class="row form-group">
                <div class="col-lg-4">
                    <label>Pilih Barang *</label>
                    <select class="form-control" name="id_barang" id="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang as $b) : ?>
                            <option value="<?= $b['id_barang'] ?>" 
                                <?= ($b['id_barang'] == $kebutuhan['id_barang']) ? 'selected' : '' ?>
                                <?= set_select('id_barang', $b['id_barang']); ?>>
                                <?= $b['nama_barang'] ?> (<?= $b['nama_jenis'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label>Tanggal Kebutuhan *</label>
                    <input type="date" class="form-control" name="tanggal" 
                           value="<?= set_value('tanggal', date('Y-m-d', strtotime($kebutuhan['tanggal']))); ?>" required>
                </div>
                <div class="col-lg-4">
                    <label>Status *</label>
                    <select class="form-control" name="status" required>
                        <option value="1" <?= ($kebutuhan['status'] == 1) ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= ($kebutuhan['status'] == 0) ? 'selected' : '' ?>>Non-Aktif</option>
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
                               value="<?= set_value('kebutuhan', $kebutuhan['kebutuhan']); ?>" 
                               required>
                        <div class="input-group-append">
                            <span class="input-group-text">Unit</span>
                        </div>
                    </div>
                    <small class="text-muted">Jumlah yang dibutuhkan</small>
                </div>
                <div class="col-lg-6">
                    <label>Informasi Barang</label>
                    <div id="barang-info" class="alert alert-info">
                        <?php if (!empty($kebutuhan['nama_barang'])): ?>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="font-weight-bold"><?= $kebutuhan['nama_barang'] ?></h6>
                                </div>
                                <div class="col-6">
                                    <small class="d-block">
                                        <strong>Stok Tersedia:</strong> 
                                        <?= number_format($kebutuhan['stok'], 0, ',', '.') ?>
                                    </small>
                                    <small class="d-block">
                                        <strong>Satuan:</strong> 
                                        <?= !empty($kebutuhan['nama_satuan']) ? $kebutuhan['nama_satuan'] : '-' ?>
                                    </small>
                                </div>
                                <div class="col-6">
                                    <?php 
                                    $stok = (float) $kebutuhan['stok'];
                                    $kebutuhan_jumlah = (float) $kebutuhan['kebutuhan'];
                                    $persentase = $stok > 0 ? ($kebutuhan_jumlah / $stok) * 100 : 0;
                                    ?>
                                    <small class="d-block">
                                        <strong>Persentase:</strong> 
                                        <?= number_format($persentase, 2, ',', '.') ?>%
                                    </small>
                                    <small class="text-muted">
                                        (<?= $kebutuhan_jumlah ?> ÷ <?= $stok ?>) × 100
                                    </small>
                                </div>
                            </div>
                        <?php else: ?>
                            <small><i class="fa fa-info-circle"></i> Pilih barang untuk melihat informasi</small>
                        <?php endif; ?>
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
                            Update Kebutuhan
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
    const kebutuhanInput = document.querySelector('input[name="kebutuhan"]');
    
    // Function untuk update informasi barang
    function updateBarangInfo(selectedId, kebutuhanValue = null) {
        if (!selectedId) {
            barangInfo.innerHTML = '<small><i class="fa fa-info-circle"></i> Pilih barang untuk melihat informasi</small>';
            return;
        }
        
        // Jika kebutuhanValue null, ambil dari input
        if (kebutuhanValue === null && kebutuhanInput) {
            kebutuhanValue = parseFloat(kebutuhanInput.value) || 0;
        }
        
        // Tampilkan loading
        barangInfo.innerHTML = '<small><i class="fa fa-spinner fa-spin"></i> Memuat informasi barang...</small>';
        
        // Fetch data barang menggunakan AJAX
        fetch(`<?= base_url('kebutuhan/get_stok_barang/') ?>${selectedId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Hitung persentase
                    const stok = parseFloat(data.data.stok);
                    const kebutuhan = parseFloat(kebutuhanValue) || 0;
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
    }
    
    // Event listener untuk dropdown barang
    barangSelect.addEventListener('change', function() {
        updateBarangInfo(this.value);
    });
    
    // Event listener untuk input kebutuhan
    if (kebutuhanInput) {
        kebutuhanInput.addEventListener('input', function() {
            if (barangSelect.value) {
                updateBarangInfo(barangSelect.value, this.value);
            }
        });
    }
    
    // Inisialisasi info barang jika sudah ada barang terpilih
    if (barangSelect.value) {
        updateBarangInfo(barangSelect.value, <?= $kebutuhan['kebutuhan'] ?>);
    }
});
</script>