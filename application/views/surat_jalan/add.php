<div class="row justify-content-center">
    <div class="col">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">Tambah Surat Jalan</h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('SuratJalan') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon"><i class="fa fa-arrow-left"></i></span>
                            <span class="text">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <!-- GUNAKAN FORM_OPEN_MULTIPART SEPERTI CONTOH BAST -->
                <?= form_open_multipart('SuratJalan/add') ?>

                    <!-- PILIH BARANG MASUK -->
                    <div class="form-group">
                        <label>Pilih Barang Masuk *</label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-barang-masuk">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select-all">
                                                <label class="custom-control-label" for="select-all"></label>
                                            </div>
                                        </th>
                                        <th>No. Transaksi Barang Masuk</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Supplier</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($barang_masuk)): ?>
                                        <?php foreach ($barang_masuk as $bm): ?>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input barang-checkbox" 
                                                           name="id_barang_masuk[]" 
                                                           value="<?= $bm['id_barang_masuk'] ?>" 
                                                           id="barang_<?= $bm['id_barang_masuk'] ?>">
                                                    <label class="custom-control-label" for="barang_<?= $bm['id_barang_masuk'] ?>"></label>
                                                </div>
                                            </td>
                                            <td><?= !empty($bm['no_transaksi']) ? $bm['no_transaksi'] : 'BM-' . $bm['id_barang_masuk'] ?></td>
                                            <td><?= isset($bm['tanggal_masuk']) ? date('d/m/Y', strtotime($bm['tanggal_masuk'])) : '-' ?></td>
                                            <td><?= !empty($bm['barang_id']) ? $bm['barang_id'] : '-' ?></td>
                                            <td><?= !empty($bm['nama_barang']) ? $bm['nama_barang'] : '-' ?></td>
                                            <td><?= !empty($bm['nama_supplier']) ? $bm['nama_supplier'] : '-' ?></td>
                                            <td><?= !empty($bm['jumlah_masuk']) ? $bm['jumlah_masuk'] : '0' ?></td>
                                            <td><?= !empty($bm['nama_satuan']) ? $bm['nama_satuan'] : '-' ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada barang masuk yang tersedia</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?= form_error('id_barang_masuk[]', '<small class="text-danger">', '</small>'); ?>
                        <small class="form-text text-muted">Pilih satu atau lebih barang masuk untuk dimasukkan ke Surat Jalan</small>
                    </div>

                    <!-- KETERANGAN -->
                    <div class="form-group">
                        <label for="keterangan">Keterangan *</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="4" placeholder="Masukkan keterangan surat jalan" required><?= set_value('keterangan') ?></textarea>
                        <?= form_error('keterangan', '<small class="text-danger">', '</small>') ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- FOTO BUKTI -->
                            <div class="form-group">
                                <label for="foto_bukti">Foto Bukti</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_bukti" name="foto_bukti" accept=".jpg,.jpeg,.png,.gif">
                                    <label class="custom-file-label" for="foto_bukti" id="foto_bukti_label">Pilih file...</label>
                                </div>
                                <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                                <?= form_error('foto_bukti', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- DOKUMENTASI MULTIPLE, DINAMIS -->
                            <div class="form-group">
                                <label for="dokumentasi_files">Dokumentasi (Multiple)</label>
                                <div id="dokumentasi-wrapper">
                                    <div class="input-group mb-2 dokumentasi-row">
                                        <input type="file" name="dokumentasi_files[]" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                                        <div class="input-group-append">
                                            <button class="btn btn-success btn-add-dok" type="button"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">Format: JPG, PNG, GIF, PDF, DOC, DOCX (Max: 5MB per file)</small>
                            </div>
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div class="row mt-3">
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon"><i class="fa fa-save"></i></span>
                                <span class="text">Simpan Surat Jalan</span>
                            </button>
                            <a href="<?= base_url('SuratJalan') ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
// Script untuk select all checkbox (seperti contoh BAST)
document.getElementById('select-all').addEventListener('click', function() {
    var checkboxes = document.getElementsByClassName('barang-checkbox');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = this.checked;
    }
});

// Validasi minimal satu checkbox terpilih (seperti contoh BAST)
document.querySelector('form').addEventListener('submit', function(e) {
    var checkboxes = document.getElementsByClassName('barang-checkbox');
    var checked = false;
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            checked = true;
            break;
        }
    }
    if (!checked) {
        e.preventDefault();
        alert('Pilih minimal satu barang masuk!');
    }
});

// Untuk menampilkan nama file di custom file input (seperti contoh BAST)
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto_bukti');
    const fotoLabel = document.getElementById('foto_bukti_label');
    
    fotoInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            fotoLabel.innerText = this.files[0].name;
        } else {
            fotoLabel.innerText = 'Pilih file...';
        }
    });
});

// Dynamic dokumentasi fields (tetap pakai jQuery jika sudah ada)
$(document).ready(function() {
    $(document).on('click', '.btn-add-dok', function() {
        const html = `<div class="input-group mb-2 dokumentasi-row">
            <input type="file" name="dokumentasi_files[]" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
            <div class="input-group-append">
                <button class="btn btn-danger btn-remove-dok" type="button"><i class="fa fa-minus"></i></button>
            </div>
        </div>`;
        $('#dokumentasi-wrapper').append(html);
    });

    $(document).on('click', '.btn-remove-dok', function() {
        $(this).closest('.dokumentasi-row').remove();
    });
});
</script>