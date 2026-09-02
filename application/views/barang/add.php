<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Tambah Barang
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('barang/add', [], ['stok' => 0]); ?>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="id_barang">ID Barang <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input readonly value="<?= set_value('id_barang', $id_barang); ?>" name="id_barang" id="id_barang" type="text" class="form-control">
                        <?= form_error('id_barang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input value="<?= set_value('nama_barang'); ?>" name="nama_barang" id="nama_barang" type="text" class="form-control" placeholder="Masukkan nama barang...">
                        <?= form_error('nama_barang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="merk">Merk</label>
                    <div class="col-md-9">
                        <input value="<?= set_value('merk'); ?>" name="merk" id="merk" type="text" class="form-control" placeholder="Masukkan merk barang...">
                        <?= form_error('merk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="lokasi">Lokasi</label>
                    <div class="col-md-9">
                        <input value="<?= set_value('lokasi'); ?>" name="lokasi" id="lokasi" type="text" class="form-control" placeholder="Masukkan lokasi penyimpanan...">
                        <?= form_error('lokasi', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="jenis_id">Jenis Barang <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <select name="jenis_id" id="jenis_id" class="custom-select">
                                <option value="" selected disabled>Pilih Jenis Barang</option>
                                <?php foreach ($jenis as $j) : ?>
                                    <option <?= set_select('jenis_id', $j['id_jenis']) ?> value="<?= $j['id_jenis'] ?>"><?= $j['nama_jenis'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-outline-primary" href="<?= base_url('jenis/add'); ?>" target="_blank" title="Tambah Jenis Baru">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <?= form_error('jenis_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="satuan_id">Satuan Barang <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <select name="satuan_id" id="satuan_id" class="custom-select">
                                <option value="" selected disabled>Pilih Satuan Barang</option>
                                <?php foreach ($satuan as $s) : ?>
                                    <option <?= set_select('satuan_id', $s['id_satuan']) ?> value="<?= $s['id_satuan'] ?>"><?= $s['nama_satuan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-outline-primary" href="<?= base_url('satuan/add'); ?>" target="_blank" title="Tambah Satuan Baru">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <?= form_error('satuan_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="stok_minimum">Stok Minimum <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input value="<?= set_value('stok_minimum', 0); ?>" name="stok_minimum" id="stok_minimum" type="number" min="0" class="form-control" placeholder="Masukkan batas minimum stok...">
                        <small class="form-text text-muted">Peringatan stok menipis jika stok barang mencapai atau di bawah angka ini</small>
                        <?= form_error('stok_minimum', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="foto_barang">Foto Barang</label>
                    <div class="col-md-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto_barang" name="foto_barang" accept="image/*">
                            <label class="custom-file-label" for="foto_barang" id="foto_barang_label">Pilih file foto...</label>
                        </div>
                        <div class="mt-1">
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</small>
                        </div>
                        <?= form_error('foto_barang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fa fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
                
                <div class="row form-group">
                    <div class="col-md-9 offset-md-3">
                        <small class="text-muted"><span class="text-danger">*</span> Wajib diisi</small>
                    </div>
                </div>
                
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File input label
    const fileInput = document.getElementById('foto_barang');
    const fileLabel = document.getElementById('foto_barang_label');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                fileLabel.innerText = this.files[0].name;
            } else {
                fileLabel.innerText = 'Pilih file foto...';
            }
        });
    }
    
    // Reset form
    const resetButton = document.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            setTimeout(function() {
                if (fileLabel) {
                    fileLabel.innerText = 'Pilih file foto...';
                }
            }, 0);
        });
    }
    
    // Focus on first input
    const firstInput = document.querySelector('input:not([readonly])');
    if (firstInput) {
        firstInput.focus();
    }
});
</script>