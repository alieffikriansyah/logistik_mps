<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Barang
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
                <?= form_open_multipart('barang/edit/' . $barang['id_barang'], [], ['stok' => $barang['stok'], 'id_barang' => $barang['id_barang']]); ?>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="id_barang">ID Barang</label>
                    <div class="col-md-9">
                        <input readonly value="<?= $barang['id_barang']; ?>" type="text" class="form-control bg-light">
                        <small class="form-text text-muted">ID Barang tidak dapat diubah</small>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input value="<?= set_value('nama_barang', $barang['nama_barang']); ?>" name="nama_barang" id="nama_barang" type="text" class="form-control" placeholder="Masukkan nama barang...">
                        <?= form_error('nama_barang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="ukuran">Ukuran</label>
                    <div class="col-md-9">
                        <input value="<?= set_value('ukuran', $barang['ukuran']); ?>" name="ukuran" id="ukuran" type="text" class="form-control" placeholder="Masukkan ukuran barang (contoh: 1500ML, XL, 10x10, dsb)...">
                        <?= form_error('ukuran', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="satuan_id">Satuan Barang <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <select name="satuan_id" id="satuan_id" class="custom-select">
                                <option value="" disabled>Pilih Satuan Barang</option>
                                <?php foreach ($satuan as $s) : ?>
                                    <option <?= set_select('satuan_id', $s['id_satuan'], ($barang['satuan_id'] == $s['id_satuan'])); ?> value="<?= $s['id_satuan'] ?>"><?= $s['nama_satuan'] ?></option>
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
                    <label class="col-md-3 text-md-right" for="merk">Merk</label>
                    <div class="col-md-9">
                        <input value="<?= set_value('merk', $barang['merk']); ?>" name="merk" id="merk" type="text" class="form-control" placeholder="Masukkan merk barang...">
                        <?= form_error('merk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="lokasi">Lokasi</label>
                    <div class="col-md-9">
                        <input value="<?= set_value('lokasi', $barang['lokasi']); ?>" name="lokasi" id="lokasi" type="text" class="form-control" placeholder="Masukkan lokasi penyimpanan...">
                        <?= form_error('lokasi', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="jenis_id">Jenis Barang <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <select name="jenis_id" id="jenis_id" class="custom-select">
                                <option value="" disabled>Pilih Jenis Barang</option>
                                <?php foreach ($jenis as $j) : ?>
                                    <option <?= set_select('jenis_id', $j['id_jenis'], ($barang['jenis_id'] == $j['id_jenis'])); ?> value="<?= $j['id_jenis'] ?>"><?= $j['nama_jenis'] ?></option>
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
                    <label class="col-md-3 text-md-right" for="stok">Stok Saat Ini</label>
                    <div class="col-md-9">
                        <input readonly value="<?= $barang['stok']; ?>" type="number" class="form-control bg-light">
                        <small class="form-text text-muted">Stok hanya dapat diubah melalui transaksi barang masuk/keluar</small>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="stok_minimum">Stok Minimum <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input value="<?= set_value('stok_minimum', $barang['stok_minimum']); ?>" name="stok_minimum" id="stok_minimum" type="number" min="0" class="form-control" placeholder="Masukkan batas minimum stok...">
                        <small class="form-text text-muted">Peringatan stok menipis jika stok barang mencapai atau di bawah angka ini</small>
                        <?= form_error('stok_minimum', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-3 text-md-right">Foto Barang Saat Ini</label>
                    <div class="col-md-9">
                        <?php if (!empty($barang['foto_barang']) && file_exists(FCPATH . 'assets/uploads/fotobarang/' . $barang['foto_barang'])) : ?>
                            <div class="mb-3">
                                <img src="<?= base_url('assets/uploads/fotobarang/' . $barang['foto_barang']); ?>" 
                                    alt="Foto Barang" 
                                    class="img-thumbnail" 
                                    style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="mt-1">
                                    <small class="text-muted"><?= $barang['foto_barang']; ?></small>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="mb-3">
                                <span class="badge badge-secondary">Tidak ada foto</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto_barang" name="foto_barang" accept="image/*">
                            <label class="custom-file-label" for="foto_barang" id="foto_barang_label">Pilih file foto baru...</label>
                        </div>
                        <div class="mt-1">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</small>
                        </div>
                        <?= form_error('foto_barang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fa fa-redo"></i> Reset
                        </button>
                        <a href="<?= base_url('barang'); ?>" class="btn btn-light">
                            <i class="fa fa-times"></i> Batal
                        </a>
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
                fileLabel.innerText = 'Pilih file foto baru...';
            }
        });
    }
    
    // Reset form
    const resetButton = document.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            setTimeout(function() {
                if (fileLabel) {
                    fileLabel.innerText = 'Pilih file foto baru...';
                }
            }, 0);
        });
    }
});
</script>