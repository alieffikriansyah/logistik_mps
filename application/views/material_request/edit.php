<head>
  <!-- CSS Select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  
  <!-- jQuery wajib dimuat dulu -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- JS Select2 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>

<?php
$default_tipe = (!empty($mr['barang_minta']) && empty($mr['id_barang'])) ? 'baru' : 'daftar';
$tipe_selected = set_value('tipe_permintaan', $default_tipe);
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 m-0 font-weight-bold text-primary">Form Edit Material Request</h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('material_request') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon"><i class="fa fa-arrow-left"></i></span>
                            <span class="text">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open_multipart('', [], ['id_mr' => $mr['id_mr']]); ?>

                <!-- Pilihan Tipe Permintaan -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right font-weight-bold">Tipe Permintaan</label>
                    <div class="col-md-9">
                        <div class="btn-group btn-group-toggle w-100 mb-1" data-toggle="buttons">
                            <label class="btn btn-outline-primary <?= ($tipe_selected == 'daftar') ? 'active' : ''; ?>" id="btn_tipe_daftar">
                                <input type="radio" name="tipe_permintaan" id="tipe_daftar" value="daftar" autocomplete="off" <?= ($tipe_selected == 'daftar') ? 'checked' : ''; ?>>
                                <i class="fa fa-list mr-1"></i> Pilih Barang dari Daftar
                            </label>
                            <label class="btn btn-outline-primary <?= ($tipe_selected == 'baru') ? 'active' : ''; ?>" id="btn_tipe_baru">
                                <input type="radio" name="tipe_permintaan" id="tipe_baru" value="baru" autocomplete="off" <?= ($tipe_selected == 'baru') ? 'checked' : ''; ?>>
                                <i class="fa fa-plus-circle mr-1"></i> Minta Barang Baru
                            </label>
                        </div>
                        <small class="form-text text-muted">Pilih apakah barang sudah ada di master barang atau mengajukan pengadaan barang baru.</small>
                    </div>
                </div>

                <!-- Opsi 1: Pilih Barang dari Daftar -->
                <div id="section_pilih_daftar" style="<?= ($tipe_selected == 'baru') ? 'display:none;' : ''; ?>">
                    <div class="row form-group">
                        <label class="col-md-3 text-md-right font-weight-bold" for="id_barang">Pilih Barang dari Daftar</label>
                        <div class="col-md-9">
                            <select name="id_barang" id="id_barang" class="form-control">
                                <option value="">Pilih Barang (Jika ada di daftar)</option>
                                <?php foreach ($barang as $b) : ?>
                                    <option value="<?= $b['id_barang']; ?>" 
                                        data-jenis="<?= $b['jenis_id'] ?>"
                                        data-nama-jenis="<?= htmlspecialchars($b['nama_jenis'] ?? '') ?>"
                                        data-satuan="<?= $b['satuan_id'] ?>"
                                        data-nama-satuan="<?= htmlspecialchars($b['nama_satuan'] ?? '') ?>"
                                        <?= ($mr['id_barang'] == $b['id_barang']) ? 'selected' : ''; ?>
                                        <?= set_select('id_barang', $b['id_barang']); ?>>
                                        <?= $b['id_barang'] . ' | ' . $b['nama_barang']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Pilih barang jika sudah ada di master barang</small>
                            <?= form_error('id_barang', '<small class="text-danger d-block">', '</small>'); ?>

                            <!-- Info Detail Barang Terpilih -->
                            <div id="info_barang_detail" class="mt-2 p-2 bg-light border rounded" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <span class="mr-3"><i class="fa fa-tag text-primary mr-1"></i> <strong>Jenis:</strong> <span id="info_jenis" class="badge badge-primary">-</span></span>
                                    <span><i class="fa fa-cube text-info mr-1"></i> <strong>Satuan:</strong> <span id="info_satuan" class="badge badge-info">-</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opsi 2: Input Barang Baru Manual -->
                <div id="section_barang_baru" style="<?= ($tipe_selected == 'daftar') ? 'display:none;' : ''; ?>">
                    <div class="row form-group">
                        <label class="col-md-3 text-md-right font-weight-bold" for="barang_minta">Minta Barang Baru</label>
                        <div class="col-md-9">
                            <input value="<?= set_value('barang_minta', $mr['barang_minta']); ?>" name="barang_minta" id="barang_minta" type="text" class="form-control" placeholder="Masukkan nama barang yang diminta">
                            <small class="form-text text-muted">Isi nama barang baru jika tidak ada di daftar master barang</small>
                            <?= form_error('barang_minta', '<small class="text-danger d-block">', '</small>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 text-md-right font-weight-bold" for="id_jenis">Jenis Barang</label>
                        <div class="col-md-9">
                            <select name="id_jenis" id="id_jenis" class="form-control">
                                <option value="" disabled>Pilih Jenis Barang</option>
                                <?php foreach ($jenis as $j) : ?>
                                    <option value="<?= $j['id_jenis']; ?>" 
                                        <?= ($mr['id_jenis'] == $j['id_jenis']) ? 'selected' : ''; ?>
                                        <?= set_select('id_jenis', $j['id_jenis']); ?>>
                                        <?= $j['nama_jenis']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('id_jenis', '<small class="text-danger d-block">', '</small>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 text-md-right font-weight-bold" for="id_satuan">Satuan Barang</label>
                        <div class="col-md-9">
                            <select name="id_satuan" id="id_satuan" class="form-control">
                                <option value="" disabled>Pilih Satuan Barang</option>
                                <?php foreach ($satuan as $s) : ?>
                                    <option value="<?= $s['id_satuan']; ?>" 
                                        <?= ($mr['id_satuan'] == $s['id_satuan']) ? 'selected' : ''; ?>
                                        <?= set_select('id_satuan', $s['id_satuan']); ?>>
                                        <?= $s['nama_satuan']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('id_satuan', '<small class="text-danger d-block">', '</small>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3 text-md-right font-weight-bold" for="link_rekomendasi">Link Rekomendasi</label>
                        <div class="col-md-9">
                            <input name="link_rekomendasi" id="link_rekomendasi" value="<?= set_value('link_rekomendasi', $mr['link_rekomendasi']); ?>" type="url" class="form-control" placeholder="https://example.com">
                            <small class="form-text text-muted">Opsional - link rekomendasi pembelian barang baru</small>
                            <?= form_error('link_rekomendasi', '<small class="text-danger d-block">', '</small>'); ?>
                        </div>
                    </div>
                </div>

                <!-- Jenis MR -->
                <?php
                  $jenis_mr_edit = set_value('jenis_mr', $mr['jenis_mr'] ?? '');
                  $jenis_mr_opts = ['kontrak' => 'Kontrak', 'si' => 'SI', 'biasa' => 'Permintaan Biasa', 'lainnya' => 'Lainnya'];
                ?>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right font-weight-bold">Jenis MR</label>
                    <div class="col-md-9">
                        <div class="d-flex flex-wrap" id="jenis_mr_group">
                          <?php foreach ($jenis_mr_opts as $val => $label) : ?>
                            <div class="custom-control custom-radio mr-3 mb-1">
                              <input type="radio" id="jenis_mr_<?= $val ?>" name="jenis_mr" value="<?= $val ?>" class="custom-control-input jenis-mr-radio"
                                <?= ($jenis_mr_edit == $val) ? 'checked' : '' ?>>
                              <label class="custom-control-label" for="jenis_mr_<?= $val ?>"><?= $label ?></label>
                            </div>
                          <?php endforeach; ?>
                        </div>
                        <?= form_error('jenis_mr', '<small class="text-danger d-block">', '</small>'); ?>

                        <!-- Field Perihal (muncul jika pilih Lainnya) -->
                        <div id="section_perihal" style="display: <?= ($jenis_mr_edit == 'lainnya') ? 'block' : 'none'; ?>;" class="mt-2">
                          <textarea name="perihal" id="perihal" rows="2" class="form-control" placeholder="Tuliskan perihal / keterangan tambahan jenis MR..."><?= set_value('perihal', $mr['perihal'] ?? ''); ?></textarea>
                          <?= form_error('perihal', '<small class="text-danger d-block">', '</small>'); ?>
                          <small class="form-text text-muted">Jelaskan perihal permintaan material request ini.</small>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right font-weight-bold" for="stok">Jumlah Diminta</label>
                    <div class="col-md-9">
                        <input name="stok" id="stok" type="number" value="<?= set_value('stok', $mr['stok']); ?>" class="form-control" placeholder="Jumlah yang diminta" step="0.01" min="0.01" required>
                        <?= form_error('stok', '<small class="text-danger d-block">', '</small>'); ?>
                    </div>
                </div>

                <!-- Keterangan (Textarea) -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right font-weight-bold" for="keterangan">Keterangan</label>
                    <div class="col-md-9">
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Masukkan keterangan atau catatan material request..."><?= set_value('keterangan', $mr['keterangan']); ?></textarea>
                        <?= form_error('keterangan', '<small class="text-danger d-block">', '</small>'); ?>
                    </div>
                </div>

                <!-- Upload File Lampiran Tambahan (Bisa Tambah Banyak File) -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right font-weight-bold">
                        Upload File Lampiran
                        <br><small class="text-muted font-weight-normal">(Opsional)</small>
                    </label>
                    <div class="col-md-9">
                        <div class="mb-2">
                            <span class="badge badge-light border text-success mr-1"><i class="fa fa-image mr-1"></i> Gambar</span>
                            <span class="badge badge-light border text-danger mr-1"><i class="fa fa-file-pdf mr-1"></i> PDF</span>
                            <span class="badge badge-light border text-success mr-1"><i class="fa fa-file-excel mr-1"></i> Excel</span>
                            <span class="badge badge-light border text-primary mr-1"><i class="fa fa-file-word mr-1"></i> Word</span>
                        </div>

                        <div id="edit_file_inputs_container">
                            <!-- Baris File 1 -->
                            <div class="input-group mb-2 edit-file-row">
                                <div class="custom-file">
                                    <input type="file" name="lampiran_files[]" class="custom-file-input edit-file-input" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.csv,.doc,.docx">
                                    <label class="custom-file-label text-truncate">Pilih file...</label>
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger btn-remove-edit-file" title="Hapus baris file ini" disabled>
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah File & Ringkasan -->
                        <div class="d-flex justify-content-between align-items-center mt-2 mb-1">
                            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold" id="btn_edit_add_file_row">
                                <i class="fa fa-plus-circle mr-1"></i> Tambah File
                            </button>
                            <small class="text-muted font-weight-bold" id="edit_file_count_summary">0 file dipilih</small>
                        </div>

                        <small class="form-text text-muted">
                            Klik <strong>+ Tambah File</strong> untuk menambah file lagi. Format yang didukung: <strong>Gambar (JPG, PNG), PDF, Excel (XLS, XLSX), Word (DOC, DOCX)</strong> (maks 10MB per file).
                        </small>

                        <!-- Daftar File yang Sudah Terupload -->
                        <?php if (!empty($files)) : ?>
                            <div class="mt-3">
                                <div class="small font-weight-bold text-primary mb-2"><i class="fa fa-paperclip mr-1"></i> File Lampiran yang Sudah Terupload:</div>
                                <ul class="list-group" id="existing_files_list">
                                    <?php foreach ($files as $f) : 
                                        $icon = 'fa-file text-secondary';
                                        if ($f['tipe_file'] == 'image') $icon = 'fa-file-image text-success';
                                        elseif ($f['tipe_file'] == 'pdf') $icon = 'fa-file-pdf text-danger';
                                        elseif ($f['tipe_file'] == 'excel') $icon = 'fa-file-excel text-success';
                                        elseif ($f['tipe_file'] == 'word') $icon = 'fa-file-word text-primary';
                                        $url = base_url('assets/uploads/material_request/' . $f['nama_file']);
                                    ?>
                                        <li class="list-group-item py-2 d-flex justify-content-between align-items-center" id="file_row_<?= $f['id_file_mr']; ?>">
                                            <div>
                                                <i class="fa <?= $icon; ?> mr-2"></i>
                                                <a href="<?= $url; ?>" target="_blank" class="font-weight-bold text-dark">
                                                    <?= htmlspecialchars($f['nama_asli'] ?? $f['nama_file']); ?>
                                                </a>
                                                <small class="text-muted ml-2">(<?= $f['ukuran_file']; ?> KB)</small>
                                            </div>
                                            <div>
                                                <a href="<?= $url; ?>" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2 mr-1" title="Lihat/Download"><i class="fa fa-download"></i></a>
                                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-delete-file-edit" data-id="<?= $f['id_file_mr']; ?>" title="Hapus"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right font-weight-bold" for="realisasi">Status</label>
                    <div class="col-md-9">
                        <select name="realisasi" id="realisasi" class="form-control">
                            <?php foreach ($status_list as $key => $label) : ?>
                                <option value="<?= $key; ?>" 
                                    <?= ($mr['realisasi'] == $key) ? 'selected' : ''; ?>
                                    <?= set_select('realisasi', $key); ?>>
                                    <?= $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('realisasi', '<small class="text-danger d-block">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group mt-4">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save mr-1"></i> Simpan Perubahan</button>
                        <button type="reset" class="btn btn-secondary px-3"><i class="fa fa-undo mr-1"></i> Reset</button>
                        <a href="<?= base_url('material_request') ?>" class="btn btn-light border px-3">Kembali</a>
                    </div>
                </div>

                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#id_barang').select2({
        placeholder: "Pilih Barang (Jika ada di daftar)",
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Data tidak ditemukan";
            }
        }
    });

    $('#id_jenis').select2({
        placeholder: "Pilih Jenis Barang",
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Data tidak ditemukan";
            }
        }
    });

    $('#id_satuan').select2({
        placeholder: "Pilih Satuan Barang",
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Data tidak ditemukan";
            }
        }
    });

    $('#realisasi').select2({
        placeholder: "Pilih Status",
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Data tidak ditemukan";
            }
        }
    });

    // Dynamic Repeater Tambah File pada Edit
    function createEditFileRow() {
        return $(`
            <div class="input-group mb-2 edit-file-row">
                <div class="custom-file">
                    <input type="file" name="lampiran_files[]" class="custom-file-input edit-file-input" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.csv,.doc,.docx">
                    <label class="custom-file-label text-truncate">Pilih file...</label>
                </div>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-danger btn-remove-edit-file" title="Hapus baris file ini">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        `);
    }

    function checkEditRemoveButtons() {
        const rows = $('.edit-file-row');
        if (rows.length > 1) {
            rows.find('.btn-remove-edit-file').prop('disabled', false);
        } else {
            rows.find('.btn-remove-edit-file').prop('disabled', true);
        }
    }

    function updateEditSummaryCount() {
        let count = 0;
        $('.edit-file-input').each(function() {
            if (this.files && this.files.length > 0) {
                count += this.files.length;
            }
        });
        $('#edit_file_count_summary').text(count + ' file dipilih');
    }

    $('#btn_edit_add_file_row').on('click', function() {
        $('#edit_file_inputs_container').append(createEditFileRow());
        checkEditRemoveButtons();
    });

    $(document).on('click', '.btn-remove-edit-file', function() {
        if ($('.edit-file-row').length > 1) {
            $(this).closest('.edit-file-row').remove();
            checkEditRemoveButtons();
            updateEditSummaryCount();
        }
    });

    $(document).on('change', '.edit-file-input', function() {
        const file = this.files[0];
        if (file) {
            const sizeKb = (file.size / 1024).toFixed(1) + ' KB';
            $(this).next('.custom-file-label').text(file.name + ' (' + sizeKb + ')');
        } else {
            $(this).next('.custom-file-label').text('Pilih file...');
        }
        updateEditSummaryCount();
    });

    // Hapus file yang sudah terupload pada halaman edit
    $(document).on('click', '.btn-delete-file-edit', function() {
        const fileId = $(this).data('id');
        if (!confirm('Yakin ingin menghapus file lampiran ini?')) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true);

        $.post('<?= base_url('material_request/delete_file_ajax/'); ?>' + fileId, function(response) {
            if (response.status) {
                $('#file_row_' + fileId).fadeOut(300, function() {
                    $(this).remove();
                });
            } else {
                alert(response.message || 'Gagal menghapus file');
                btn.prop('disabled', false);
            }
        }, 'json').fail(function() {
            alert('Gagal menghubungi server');
            btn.prop('disabled', false);
        });
    });

    function toggleMode(mode) {
        if (mode === 'daftar') {
            $('#section_pilih_daftar').stop(true, true).slideDown(200);
            $('#section_barang_baru').stop(true, true).slideUp(200);
            $('#barang_minta').val('');
            $('#link_rekomendasi').val('');
            updateBarangInfo();
        } else {
            $('#section_pilih_daftar').stop(true, true).slideUp(200);
            $('#section_barang_baru').stop(true, true).slideDown(200, function() {
                $('#id_jenis').select2({ width: '100%' });
                $('#id_satuan').select2({ width: '100%' });
            });
            $('#id_barang').val('').trigger('change');
            $('#info_barang_detail').hide();
        }
    }

    $('input[name="tipe_permintaan"]').on('change', function() {
        toggleMode($(this).val());
    });

    function updateBarangInfo() {
        const selected = $('#id_barang').find(':selected');
        const val = selected.val();
        if (val) {
            const jenisId = selected.data('jenis');
            const namaJenis = selected.data('nama-jenis') || '-';
            const satuanId = selected.data('satuan');
            const namaSatuan = selected.data('nama-satuan') || '-';

            $('#info_jenis').text(namaJenis);
            $('#info_satuan').text(namaSatuan);
            $('#info_barang_detail').stop(true, true).slideDown(150);

            if (jenisId) {
                $('#id_jenis').val(jenisId).trigger('change.select2');
            }
            if (satuanId) {
                $('#id_satuan').val(satuanId).trigger('change.select2');
            }
        } else {
            $('#info_barang_detail').stop(true, true).slideUp(150);
        }
    }

    $('#id_barang').on('change', function() {
        updateBarangInfo();
    });

    // Inisialisasi awal
    const initTipe = $('input[name="tipe_permintaan"]:checked').val() || 'daftar';
    toggleMode(initTipe);
    if (initTipe === 'daftar') {
        updateBarangInfo();
    }

    // Toggle Perihal jika pilih Jenis MR = Lainnya
    function togglePerihal() {
        const val = $('input[name="jenis_mr"]:checked').val();
        if (val === 'lainnya') {
            $('#section_perihal').stop(true, true).slideDown(200);
            $('#perihal').focus();
        } else {
            $('#section_perihal').stop(true, true).slideUp(200);
        }
    }

    $(document).on('change', '.jenis-mr-radio', function() {
        togglePerihal();
    });

    // Inisialisasi perihal saat load
    togglePerihal();

    // Validasi saat submit
    $('form').on('submit', function(e) {
        const mode = $('input[name="tipe_permintaan"]:checked').val();
        if (mode === 'daftar') {
            const idBarang = $('#id_barang').val();
            if (!idBarang) {
                alert('Silakan pilih barang dari daftar terlebih dahulu');
                $('#id_barang').select2('open');
                e.preventDefault();
                return false;
            }
        } else {
            const barangMinta = $('#barang_minta').val().trim();
            const idJenis = $('#id_jenis').val();
            const idSatuan = $('#id_satuan').val();

            if (!barangMinta) {
                alert('Silakan masukkan nama barang yang diminta');
                $('#barang_minta').focus();
                e.preventDefault();
                return false;
            }
            if (!idJenis) {
                alert('Silakan pilih jenis barang');
                $('#id_jenis').select2('open');
                e.preventDefault();
                return false;
            }
            if (!idSatuan) {
                alert('Silakan pilih satuan barang');
                $('#id_satuan').select2('open');
                e.preventDefault();
                return false;
            }
        }

        const jenisMr = $('input[name="jenis_mr"]:checked').val();
        if (!jenisMr) {
            alert('Silakan pilih Jenis MR terlebih dahulu');
            e.preventDefault();
            return false;
        }
        if (jenisMr === 'lainnya' && !$('#perihal').val().trim()) {
            alert('Silakan isi kolom Perihal untuk pilihan Lainnya');
            $('#perihal').focus();
            e.preventDefault();
            return false;
        }
    });
});
</script>

<style>
.select2-container--default .select2-selection--single {
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.select2-container--default .select2-selection--single:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(2.25rem + 2px);
}
.btn-group-toggle .btn {
    font-weight: 600;
    padding: 0.5rem 1rem;
}
.btn-group-toggle .btn.active {
    box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}
</style>