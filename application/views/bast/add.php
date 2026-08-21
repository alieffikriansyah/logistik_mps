<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Tambah BAST
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('bast') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <!-- UBAH FORM MENJADI MULTIPART UNTUK UPLOAD FILE -->
                <?= form_open_multipart('bast/add') ?>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="tanggal">Tanggal BAST</label>
                    <div class="col-md-4">
                        <input value="<?= date('Y-m-d'); ?>" type="text" readonly class="form-control">
                        <small class="form-text text-muted">Tanggal otomatis sesuai hari ini</small>
                    </div>
                </div>
                
                 <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="unit">Unit *</label>
                    <div class="col-md-4">
                        <select name="unit" id="unit" class="custom-select" required>
                            <option value="" selected disabled>Pilih unit</option>
                            <option value="High & Medium Voltage">HVMV</option>
                            <option value="Power Station 1">PS 1</option>
                            <option value="Power Station 2">PS 2</option>
                            <option value="Power Station 3">PS 3</option>
                            <option value="Electrical Protection">EP</option>
                            <option value="Electrical Network">EN</option>
                            <option value="North Visual Aid">NVA</option>
                            <option value="South Visual Aid">SVA</option>
                            <option value="Electrical Utility">EU</option>
                            <option value="UPS & Converter">UPS</option>
                            <option value="Terminal 1">T1</option>
                            <option value="Terminal 2">T2</option>
                            <option value="Terminal 3">T3</option>
                            <option value="Non Terminal Electrical Service">NTES</option>
                            <option value="IASS OM Electrical">OFFICE MPS</option>
                            <option value="Cleanning Technician">Cleanning Technician</option>
                        </select>
                        <?= form_error('unit', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="id_barang_keluar">Pilih Barang Keluar</label>
                    <div class="col-md-9">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                                <label class="custom-control-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th width="10%">No. Transaksi</th>
                                        <th width="10%">Tanggal Keluar</th>
                                        <th width="10%">Kode Barang</th>
                                        <th width="15%">Nama Barang</th>
                                        <th width="10%">Jenis Barang</th>
                                        <th width="10%">Jumlah</th>
                                        <th width="8%">Satuan</th>
                                        <th width="8%">Unit</th>
                                        <th width="14%">PIC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($barang_keluar)): ?>
                                        <?php foreach ($barang_keluar as $bk) : ?>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input barang-checkbox" 
                                                           name="id_barang_keluar[]" 
                                                           value="<?= $bk['id_barang_keluar'] ?>" 
                                                           id="barang_<?= $bk['id_barang_keluar'] ?>">
                                                    <label class="custom-control-label" for="barang_<?= $bk['id_barang_keluar'] ?>"></label>
                                                </div>
                                            </td>
                                            <td><?= $bk['id_barang_keluar'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($bk['tanggal_keluar'])) ?></td>
                                            <td><?= $bk['id_barang'] ?></td>
                                            <td><?= $bk['nama_barang'] ?></td>
                                            <td><?= $bk['nama_jenis'] ?></td>
                                            <td><?= $bk['jumlah_keluar'] ?></td>
                                            <td><?= $bk['nama_satuan'] ?></td>
                                            <td>
                                                <span class="badge badge-info"><?= strtoupper($bk['unit']) ?></span>
                                            </td>
                                            <td><?= $bk['nama_pic'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada barang keluar yang tersedia</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?= form_error('id_barang_keluar[]', '<small class="text-danger">', '</small>'); ?>
                        <small class="form-text text-muted">Pilih satu atau lebih barang keluar untuk dimasukkan ke BAST</small>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="dokumentasi">Dokumentasi</label>
                    <div class="col-md-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="dokumentasi" name="dokumentasi" accept="image/*">
                            <label class="custom-file-label" for="dokumentasi" id="dokumentasi_label">Pilih file dokumentasi...</label>
                        </div>
                        <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</small>
                        <?= form_error('dokumentasi', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col offset-md-3">
                        <button type="submit" class="btn btn-primary">Simpan BAST</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
// Script untuk select all checkbox
document.getElementById('selectAll').addEventListener('click', function() {
    var checkboxes = document.getElementsByClassName('barang-checkbox');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = this.checked;
    }
});

// Validasi minimal satu checkbox terpilih
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
        alert('Pilih minimal satu barang keluar!');
    }
});

// Untuk menampilkan nama file di custom file input
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('dokumentasi');
    const fileLabel = document.getElementById('dokumentasi_label');
    
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            fileLabel.innerText = this.files[0].name;
        } else {
            fileLabel.innerText = 'Pilih file dokumentasi...';
        }
    });
    
    // Reset form handler
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        setTimeout(function() {
            fileLabel.innerText = 'Pilih file dokumentasi...';
        }, 0);
    });
});
</script>