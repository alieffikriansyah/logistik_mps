<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-danger">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Data Barang Kurang Dari Stok Minimum
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-boxes"></i>
                    </span>
                    <span class="text">
                        Semua Data Barang
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card-body border-bottom bg-light">
        <form method="get" action="<?= base_url('barang/stok_minimum') ?>" class="form-inline">
            <div class="form-group mb-2 mr-2">
                <label for="jenis_filter" class="mr-2 font-weight-bold">Filter Jenis:</label>
                <select name="jenis_filter" id="jenis_filter" class="form-control form-control-sm">
                    <option value="">-- Semua Jenis --</option>
                    <?php foreach ($jenis as $j) : ?>
                        <option value="<?= $j['nama_jenis'] ?>" <?= ($selected_jenis == $j['nama_jenis']) ? 'selected' : '' ?>>
                            <?= $j['nama_jenis'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-primary mb-2">
                <i class="fa fa-filter"></i> Filter
            </button>
            <a href="<?= base_url('barang/stok_minimum') ?>" class="btn btn-sm btn-secondary mb-2 ml-1">
                <i class="fa fa-sync"></i> Reset
            </a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Ukuran</th>
                    <th>Satuan</th>
                    <th>Merk</th>
                    <th>Jenis Barang</th>
                    <th>Stok Saat Ini</th>
                    <th>Stok Min</th>
                    <th>Lokasi</th>
                    <th>Foto Barang</th>
                    <?php if (is_admin()) : ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barang) :
                    foreach ($barang as $b) :
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $b['id_barang']; ?></td>
                            <td class="font-weight-bold text-dark"><?= $b['nama_barang']; ?></td>
                            <td><?= !empty($b['ukuran']) ? $b['ukuran'] : '-' ?></td>
                            <td><?= $b['nama_satuan']; ?></td>
                            <td><?= !empty($b['merk']) ? $b['merk'] : '-' ?></td>
                            <td><?= $b['nama_jenis']; ?></td>
                            <td>
                                <span class="badge badge-danger p-2">
                                    <i class="fas fa-arrow-down mr-1"></i><?= $b['stok']; ?>
                                </span>
                            </td>
                            <td><span class="badge badge-secondary p-2"><?= $b['stok_minimum']; ?></span></td>
                            <td><?= !empty($b['lokasi']) ? $b['lokasi'] : '-' ?></td>
                            <td class="text-center">
                                <?php if (!empty($b['foto_barang']) && file_exists(FCPATH . 'assets/uploads/fotobarang/' . $b['foto_barang'])) : ?>
                                    <img src="<?= base_url('assets/uploads/fotobarang/' . $b['foto_barang']); ?>" 
                                        alt="Foto <?= $b['nama_barang']; ?>" 
                                        class="img-thumbnail" 
                                        style="width: 60px; height: 60px; object-fit: cover;" 
                                        title="<?= $b['foto_barang']; ?>"
                                        crossorigin="anonymous"
                                        data-fullpath="<?= base_url('assets/uploads/fotobarang/' . $b['foto_barang']); ?>">
                                <?php else : ?>
                                    <span class="badge badge-secondary">No Image</span>
                                <?php endif; ?>
                            </td>
                            <?php if (is_admin()) : ?>
                            <td>
                                <a href="<?= base_url('barangmasuk/add/') . $b['id_barang'] ?>" class="btn btn-success btn-sm btn-icon-split" title="Tambah Stok / Barang Masuk">
                                    <span class="icon"><i class="fa fa-plus"></i></span>
                                    <span class="text">Pasok</span>
                                </a>
                                <a href="<?= base_url('barang/edit/') . $b['id_barang'] ?>" class="btn btn-warning btn-circle btn-sm" title="Edit Barang">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="<?= is_admin() ? 12 : 11; ?>" class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                            Semua stok barang aman (tidak ada yang di bawah stok minimum).
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.img-thumbnail {
    cursor: pointer;
    transition: transform 0.2s;
    border: 1px solid #dee2e6;
}
.img-thumbnail:hover {
    transform: scale(1.1);
}
</style>

<script>
// Preview gambar modal
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.img-thumbnail');
    images.forEach(img => {
        img.addEventListener('click', function() {
            const src = this.src;
            const fileName = this.title;
            const modalId = 'imageModal-' + Math.random().toString(36).substr(2, 9);
            
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.id = modalId;
            modal.innerHTML = `
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Foto Barang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${src}" class="img-fluid" style="max-height: 70vh; max-width: 100%;" alt="Foto Barang">
                            <div class="mt-2">
                                <small class="text-muted">${fileName}</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            $(modal).modal('show');
            $(modal).on('hidden.bs.modal', function() {
                $(this).remove();
            });
        });
    });
});

// Filter form
document.getElementById('jenis_filter').addEventListener('change', function() {
    if (this.value) {
        this.form.submit();
    }
});
</script>

<script>
function getBase64FromImg(imgEl) {
    if (!imgEl || !imgEl.complete || imgEl.naturalWidth === 0) return null;
    try {
        const canvas = document.createElement('canvas');
        canvas.width = imgEl.naturalWidth;
        canvas.height = imgEl.naturalHeight;
        canvas.getContext('2d').drawImage(imgEl, 0, 0);
        return canvas.toDataURL('image/jpeg', 0.8);
    } catch (e) {
        console.warn('Gagal convert gambar ke base64:', e);
        return null;
    }
}

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable().destroy();
    }

    var table = $('#dataTable').DataTable({
        dom: "<'row px-2 px-md-4 pt-2'<'col-md-3'l><'col-md-5 text-center'B><'col-md-4'f>>" +
             "<'row'<'col-md-12'tr>>" +
             "<'row px-2 px-md-4 py-3'<'col-md-5'i><'col-md-7'p>>",
        lengthMenu: [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "All"]
        ],
        buttons: [
            {
                extend: 'copyHtml5',
                text: 'Copy',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
            },
            {
                text: '<i class="fa fa-file-excel"></i> Excel',
                className: 'dt-button buttons-excel',
                action: function () {
                    var selectedJenis = $('#jenis_filter').val() || '';
                    window.location.href = '<?= base_url("barang/export_excel_stok_minimum") ?>?jenis_filter=' + encodeURIComponent(selectedJenis);
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] },
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;

                    var tableBody = doc.content[1].table.body;
                    var fotoColIndex = 10;
                    var bodyRows = document.querySelectorAll('#dataTable tbody tr');

                    for (var i = 1; i < tableBody.length; i++) {
                        var trEl = bodyRows[i - 1];
                        if (!trEl) continue;

                        var tdList = trEl.querySelectorAll('td');
                        var imgEl = tdList[10] ? tdList[10].querySelector('img') : null;
                        var base64 = getBase64FromImg(imgEl);

                        tableBody[i][fotoColIndex] = base64
                            ? { image: base64, width: 30, height: 30 }
                            : { text: 'No Image', italics: true, color: '#999999', fontSize: 7 };
                    }

                    doc.content[1].table.widths = ['3%', '8%', '14%', '6%', '8%', '10%', '10%', '5%', '5%', '10%', '8%'];
                }
            },
            {
                extend: 'print',
                text: 'Print',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] },
                customize: function (win) {
                    var sources = [];
                    document.querySelectorAll('#dataTable tbody tr').forEach(function (tr) {
                        var img = tr.querySelectorAll('td')[10] ? tr.querySelectorAll('td')[10].querySelector('img') : null;
                        sources.push(img ? img.getAttribute('data-fullpath') : null);
                    });

                    var printTable = win.document.querySelector('table');
                    var headerCells = printTable.querySelectorAll('thead th');
                    var fotoColIdx = -1;
                    headerCells.forEach(function (th, idx) {
                        if (th.textContent.trim() === 'Foto Barang') fotoColIdx = idx;
                    });

                    if (fotoColIdx > -1) {
                        var rows = printTable.querySelectorAll('tbody tr');
                        rows.forEach(function (row, i) {
                            var cell = row.querySelectorAll('td')[fotoColIdx];
                            if (cell && sources[i]) {
                                cell.innerHTML =
                                    '<img src="' + sources[i] + '" style="width:50px;height:50px;object-fit:cover;border:1px solid #ccc;">';
                            } else if (cell) {
                                cell.textContent = 'No Image';
                            }
                        });
                    }
                }
            }
        ]
    });
});
</script>
