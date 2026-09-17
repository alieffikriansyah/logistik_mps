<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Material Request
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('material_request/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">Tambah Material Request</span>
                </a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped" id="dataTable">
            <thead class="text-center">
                <tr>
                    <th>No.</th>
                    <th>ID MR</th>
                    <th>Tanggal</th>
                    <th>Jenis MR</th>
                    <th>Barang</th>
                    <th>Barang Baru</th>
                    <th>Jenis</th>
                    <th>Satuan</th>
                    <th>Stok Diminta</th>
                    <th>Link</th>
                    <th>Lampiran File</th>
                    <th>Keterangan</th>
                    <th>Diajukan Oleh</th>
                    <th>Unit</th>
                    <th>Status</th>
                    <?php if ($user_id == '1' || $user_id == '17' || $user_id == '18' || $user_id == '19') : ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($material_request) :
                    foreach ($material_request as $mr) :
                        $total_files = intval($mr['total_files'] ?? 0);
                        $nama_item = !empty($mr['nama_barang']) ? $mr['nama_barang'] : (!empty($mr['barang_minta']) ? $mr['barang_minta'] : '-');
                ?>
                        <tr class="text-center">
                            <td><?= $no++; ?></td>
                            <td>MR<?= str_pad($mr['id_mr'], 4, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d M Y', strtotime($mr['tanggal'])); ?></td>
                            <td>
                                <?php
                                  $jenis_mr_labels = ['kontrak' => '<span class="badge badge-primary">Kontrak</span>', 'si' => '<span class="badge badge-info">SI</span>', 'biasa' => '<span class="badge badge-secondary">Permintaan Biasa</span>', 'lainnya' => '<span class="badge badge-warning text-dark">Lainnya</span>'];
                                  $jenis_mr_val = $mr['jenis_mr'] ?? '';
                                  echo $jenis_mr_labels[$jenis_mr_val] ?? '<span class="text-muted">-</span>';
                                  if ($jenis_mr_val === 'lainnya' && !empty($mr['perihal'])) :
                                ?>
                                    <br><small class="text-muted" title="<?= htmlspecialchars($mr['perihal']); ?>" data-toggle="tooltip"><?= (strlen($mr['perihal']) > 30) ? htmlspecialchars(substr($mr['perihal'], 0, 30)) . '...' : htmlspecialchars($mr['perihal']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= $mr['nama_barang'] ?? '-'; ?></td>
                            <td><?= $mr['barang_minta'] ?? '-'; ?></td>
                            <td><?= $mr['nama_jenis'] ?? '-'; ?></td>
                            <td><?= $mr['nama_satuan'] ?? '-'; ?></td>
                            <td><?= $mr['stok']; ?></td>
                            <td>
                                <?php if (!empty($mr['link_rekomendasi'])) : ?>
                                    <a href="<?= $mr['link_rekomendasi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-external-link-alt"></i> Link
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" 
                                        class="btn btn-sm <?= ($total_files > 0) ? 'btn-info' : 'btn-outline-primary'; ?> btn-modal-file" 
                                        data-id="<?= $mr['id_mr']; ?>" 
                                        data-idmr="MR<?= str_pad($mr['id_mr'], 4, '0', STR_PAD_LEFT); ?>"
                                        data-title="<?= htmlspecialchars($nama_item); ?>"
                                        data-keterangan="<?= htmlspecialchars($mr['keterangan'] ?? ''); ?>"
                                        title="Klik untuk upload atau lihat file lampiran">
                                    <i class="fa <?= ($total_files > 0) ? 'fa-paperclip' : 'fa-upload'; ?> mr-1"></i> 
                                    <span class="btn-file-text"><?= ($total_files > 0) ? $total_files . ' File' : 'Upload File'; ?></span>
                                </button>
                            </td>
                            <td class="text-left" style="max-width: 200px;">
                                <?php if (!empty($mr['keterangan'])) : ?>
                                    <span title="<?= htmlspecialchars($mr['keterangan']); ?>" data-toggle="tooltip">
                                        <?= (strlen($mr['keterangan']) > 50) ? htmlspecialchars(substr($mr['keterangan'], 0, 50)) . '...' : htmlspecialchars($mr['keterangan']); ?>
                                    </span>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $mr['nama'] ?? '-'; ?></td>
                            <td><?= $mr['role'] ?? '-'; ?></td>
                            <td><?= $mr['status_label']; ?></td>

                           <?php if ($user_id == '1' || $user_id == '17' || $user_id == '18' || $user_id == '19') : ?>
                                <td>
                                    <a href="<?= base_url('material_request/edit/') . $mr['id_mr'] ?>" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm('Yakin ingin hapus material request ini beserta seluruh file lampirannya?')" href="<?= base_url('material_request/delete/') . $mr['id_mr'] ?>" class="btn btn-danger btn-circle btn-sm" title="Hapus"><i class="fa fa-trash"></i></a>
                                </td>
                            <?php endif; ?>
                        </tr>
                <?php
                    endforeach;
                else :
                    echo '<tr><td colspan="' . ($user_id == '1' ? '15' : '14') . '" class="text-center">Data Kosong</td></tr>';
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Upload & Kelola File Lampiran -->
<div class="modal fade" id="modalUploadFile" tabindex="-1" role="dialog" aria-labelledby="modalUploadFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title font-weight-bold" id="modalUploadFileLabel">
                    <i class="fa fa-paperclip mr-2"></i> Lampiran File - <span id="modal_mr_code"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 p-3 bg-light rounded border">
                    <div class="text-xs text-uppercase font-weight-bold text-primary mb-1">Nama Barang / Permintaan:</div>
                    <div class="h6 font-weight-bold text-gray-800 mb-1" id="modal_mr_barang">-</div>
                    <div id="modal_mr_keterangan_wrapper" style="display:none;" class="mt-2 pt-2 border-top">
                        <span class="small text-muted font-weight-bold">Keterangan:</span>
                        <div class="small text-dark font-italic" id="modal_mr_keterangan"></div>
                    </div>
                </div>

                <!-- Alert Response AJAX -->
                <div id="modal_alert" class="alert alert-dismissible fade show" style="display:none;" role="alert">
                    <span id="modal_alert_message"></span>
                    <button type="button" class="close" onclick="$('#modal_alert').hide();">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Form Upload File Baru -->
                <div class="card border-left-primary shadow-sm mb-4">
                    <div class="card-header py-2 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fa fa-cloud-upload-alt mr-1"></i> Form Upload File
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="form_upload_file" enctype="multipart/form-data">
                            <input type="hidden" name="id_mr" id="modal_upload_id_mr">
                            
                            <!-- Badges format yang didukung -->
                            <div class="mb-3">
                                <span class="badge badge-light border text-success mr-1 mb-1 px-2 py-1">
                                    <i class="fa fa-image mr-1"></i> Gambar (JPG, PNG, GIF, WEBP)
                                </span>
                                <span class="badge badge-light border text-danger mr-1 mb-1 px-2 py-1">
                                    <i class="fa fa-file-pdf mr-1"></i> PDF (.pdf)
                                </span>
                                <span class="badge badge-light border text-success mr-1 mb-1 px-2 py-1">
                                    <i class="fa fa-file-excel mr-1"></i> Excel (.xls, .xlsx, .csv)
                                </span>
                                <span class="badge badge-light border text-primary mr-1 mb-1 px-2 py-1">
                                    <i class="fa fa-file-word mr-1"></i> Word (.doc, .docx)
                                </span>
                            </div>

                            <div class="form-group mb-2">
                                <label class="font-weight-bold mb-1">Pilih File Lampiran:</label>
                                
                                <!-- Container Baris File -->
                                <div id="modal_file_inputs_container">
                                    <!-- Baris File 1 -->
                                    <div class="input-group mb-2 modal-file-row">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input modal-file-input" name="files[]" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.csv,.doc,.docx">
                                            <label class="custom-file-label text-truncate">Pilih file...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger btn-remove-modal-file" title="Hapus baris file ini" disabled>
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Tambah File & Ringkasan -->
                                <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-success font-weight-bold" id="btn_modal_add_file_row">
                                        <i class="fa fa-plus-circle mr-1"></i> Tambah File
                                    </button>
                                    <small class="text-muted font-weight-bold" id="modal_file_count_summary">0 file dipilih</small>
                                </div>

                                <small class="form-text text-muted">
                                    Klik <strong>+ Tambah File</strong> untuk menambah file lagi. Anda bisa mengupload beberapa file sekaligus (maks 10MB per file).
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary mt-2" id="btn_modal_upload">
                                <i class="fa fa-upload mr-1"></i> Upload Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Card Daftar File Terupload -->
                <div class="card shadow-sm">
                    <div class="card-header py-2 bg-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-dark">
                            <i class="fa fa-folder mr-1"></i> Daftar File yang Sudah Diupload
                        </h6>
                        <span class="badge badge-info px-2 py-1" id="badge_count_files">0 File</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 50px;" class="text-center">Format</th>
                                        <th>Nama File</th>
                                        <th style="width: 110px;">Ukuran</th>
                                        <th style="width: 150px;">Tgl Upload</th>
                                        <th style="width: 110px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_modal_files">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Memuat file...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentMrId = null;
    let currentTriggerButton = null;

    function createModalFileRow() {
        return $(`
            <div class="input-group mb-2 modal-file-row">
                <div class="custom-file">
                    <input type="file" class="custom-file-input modal-file-input" name="files[]" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.csv,.doc,.docx">
                    <label class="custom-file-label text-truncate">Pilih file...</label>
                </div>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-danger btn-remove-modal-file" title="Hapus baris file ini">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        `);
    }

    function checkModalRemoveButtons() {
        const rows = $('.modal-file-row');
        if (rows.length > 1) {
            rows.find('.btn-remove-modal-file').prop('disabled', false);
        } else {
            rows.find('.btn-remove-modal-file').prop('disabled', true);
        }
    }

    function updateModalSummaryCount() {
        let count = 0;
        $('.modal-file-input').each(function() {
            if (this.files && this.files.length > 0) {
                count += this.files.length;
            }
        });
        $('#modal_file_count_summary').text(count + ' file dipilih');
    }

    // Tombol Tambah File pada modal
    $('#btn_modal_add_file_row').on('click', function() {
        $('#modal_file_inputs_container').append(createModalFileRow());
        checkModalRemoveButtons();
    });

    // Tombol Hapus Baris File pada modal
    $(document).on('click', '.btn-remove-modal-file', function() {
        if ($('.modal-file-row').length > 1) {
            $(this).closest('.modal-file-row').remove();
            checkModalRemoveButtons();
            updateModalSummaryCount();
        }
    });

    // Event ketika file dipilih pada baris modal
    $(document).on('change', '.modal-file-input', function() {
        const file = this.files[0];
        if (file) {
            const sizeKb = (file.size / 1024).toFixed(1) + ' KB';
            $(this).next('.custom-file-label').text(file.name + ' (' + sizeKb + ')');
        } else {
            $(this).next('.custom-file-label').text('Pilih file...');
        }
        updateModalSummaryCount();
    });

    function resetModalFileInputs() {
        const container = $('#modal_file_inputs_container');
        container.empty();
        container.append(createModalFileRow());
        checkModalRemoveButtons();
        updateModalSummaryCount();
    }

    // Klik tombol Upload File / Lampiran di baris tabel
    $(document).on('click', '.btn-modal-file', function() {
        currentTriggerButton = $(this);
        currentMrId = $(this).data('id');
        const mrCode = $(this).data('idmr');
        const mrTitle = $(this).data('title');

        $('#modal_upload_id_mr').val(currentMrId);
        $('#modal_mr_code').text(mrCode);
        $('#modal_mr_barang').text(mrTitle);

        const mrKet = $(this).data('keterangan');
        if (mrKet && mrKet.trim() !== '') {
            $('#modal_mr_keterangan').text(mrKet);
            $('#modal_mr_keterangan_wrapper').show();
        } else {
            $('#modal_mr_keterangan_wrapper').hide();
        }

        // Reset input file & alerts
        resetModalFileInputs();
        $('#modal_alert').hide();

        loadFiles(currentMrId);
        $('#modalUploadFile').modal('show');
    });

    // Submit form upload via AJAX
    $('#form_upload_file').on('submit', function(e) {
        e.preventDefault();

        // Pastikan minimal ada 1 file yang terisi
        let hasSelectedFile = false;
        $('.modal-file-input').each(function() {
            if (this.files && this.files.length > 0) {
                hasSelectedFile = true;
            }
        });

        if (!hasSelectedFile) {
            showAlert('warning', 'Silakan pilih minimal 1 file terlebih dahulu.');
            return;
        }

        const formData = new FormData(this);
        const submitBtn = $('#btn_modal_upload');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Mengupload...');

        $.ajax({
            url: '<?= base_url('material_request/upload_files_ajax'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                submitBtn.prop('disabled', false).html('<i class="fa fa-upload mr-1"></i> Upload Sekarang');
                if (response.status) {
                    showAlert('success', response.message);
                    resetModalFileInputs();

                    // Render ulang daftar file
                    renderFilesTable(response.files);
                    updateRowButton(response.files.length);
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function() {
                submitBtn.prop('disabled', false).html('<i class="fa fa-upload mr-1"></i> Upload Sekarang');
                showAlert('danger', 'Terjadi kesalahan pada server saat mengupload file.');
            }
        });
    });

    // Load file list via AJAX
    function loadFiles(idMr) {
        const tbody = $('#tbody_modal_files');
        tbody.html('<tr><td colspan="5" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin mr-1"></i> Memuat file lampiran...</td></tr>');

        $.getJSON('<?= base_url('material_request/get_files_ajax/'); ?>' + idMr, function(response) {
            if (response.status) {
                renderFilesTable(response.files);
                updateRowButton(response.files.length);
            } else {
                tbody.html('<tr><td colspan="5" class="text-center text-danger py-3">Gagal memuat data file.</td></tr>');
            }
        }).fail(function() {
            tbody.html('<tr><td colspan="5" class="text-center text-danger py-3">Gagal menghubungi server.</td></tr>');
        });
    }

    // Render tabel file lampiran
    function renderFilesTable(files) {
        const tbody = $('#tbody_modal_files');
        tbody.empty();
        $('#badge_count_files').text((files ? files.length : 0) + ' File');

        if (!files || files.length === 0) {
            tbody.html('<tr><td colspan="5" class="text-center text-muted py-3"><i class="fa fa-info-circle mr-1"></i> Belum ada file lampiran yang diupload.</td></tr>');
            return;
        }

        files.forEach(function(f) {
            let icon = '<i class="fa fa-file text-secondary fa-lg"></i>';
            if (f.tipe_file === 'image') {
                icon = '<i class="fa fa-file-image text-success fa-lg"></i>';
            } else if (f.tipe_file === 'pdf') {
                icon = '<i class="fa fa-file-pdf text-danger fa-lg"></i>';
            } else if (f.tipe_file === 'excel') {
                icon = '<i class="fa fa-file-excel text-success fa-lg"></i>';
            } else if (f.tipe_file === 'word') {
                icon = '<i class="fa fa-file-word text-primary fa-lg"></i>';
            }

            const sizeText = f.ukuran_file ? (f.ukuran_file > 1024 ? (f.ukuran_file / 1024).toFixed(2) + ' MB' : f.ukuran_file + ' KB') : '-';
            const dateText = f.created_at || '-';
            const originalName = f.nama_asli || f.nama_file;

            const row = $('<tr>' +
                '<td class="text-center align-middle">' + icon + '</td>' +
                '<td class="align-middle">' +
                    '<a href="' + f.url + '" target="_blank" class="font-weight-bold text-primary" title="Buka File">' +
                        escapeHtml(originalName) +
                    '</a>' +
                '</td>' +
                '<td class="align-middle text-muted small">' + sizeText + '</td>' +
                '<td class="align-middle text-muted small">' + dateText + '</td>' +
                '<td class="text-center align-middle">' +
                    '<a href="' + f.url + '" target="_blank" class="btn btn-sm btn-outline-primary mr-1" title="Lihat/Download"><i class="fa fa-download"></i></a>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-file" data-id="' + f.id_file_mr + '" title="Hapus"><i class="fa fa-trash"></i></button>' +
                '</td>' +
            '</tr>');

            tbody.append(row);
        });
    }

    // Hapus file lampiran
    $(document).on('click', '.btn-delete-file', function() {
        const fileId = $(this).data('id');
        if (!confirm('Yakin ingin menghapus file lampiran ini?')) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true);

        $.post('<?= base_url('material_request/delete_file_ajax/'); ?>' + fileId, function(response) {
            if (response.status) {
                showAlert('success', 'File berhasil dihapus.');
                loadFiles(currentMrId);
            } else {
                showAlert('danger', response.message);
                btn.prop('disabled', false);
            }
        }, 'json').fail(function() {
            showAlert('danger', 'Gagal menghapus file.');
            btn.prop('disabled', false);
        });
    });

    // Helper update tampilan tombol di baris tabel utama
    function updateRowButton(count) {
        if (currentTriggerButton && currentTriggerButton.length) {
            if (count > 0) {
                currentTriggerButton.removeClass('btn-outline-primary').addClass('btn-info');
                currentTriggerButton.find('i').removeClass('fa-upload').addClass('fa-paperclip');
                currentTriggerButton.find('.btn-file-text').text(count + ' File');
            } else {
                currentTriggerButton.removeClass('btn-info').addClass('btn-outline-primary');
                currentTriggerButton.find('i').removeClass('fa-paperclip').addClass('fa-upload');
                currentTriggerButton.find('.btn-file-text').text('Upload File');
            }
        }
    }

    // Helper Alert
    function showAlert(type, message) {
        const alertBox = $('#modal_alert');
        alertBox.removeClass('alert-success alert-danger alert-warning alert-info')
                .addClass('alert-' + type);
        $('#modal_alert_message').html(message);
        alertBox.stop(true, true).slideDown(200);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }
});
</script>