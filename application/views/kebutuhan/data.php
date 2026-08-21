<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    <i class="fa fa-calculator"></i> Data Kebutuhan dengan Perhitungan Persentase
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('kebutuhan/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Tambah Kebutuhan
                    </span>
                </a>
                <a href="<?= base_url('kebutuhan/laporan') ?>" class="btn btn-sm btn-info btn-icon-split ml-2">
                    <span class="icon">
                        <i class="fa fa-chart-bar"></i>
                    </span>
                    <span class="text">
                        Laporan
                    </span>
                </a>
                <a href="<?= base_url('kebutuhan/export_excel') . '?jenis_filter=' . $selected_jenis . '&tahun_filter=' . $selected_tahun ?>" 
                   class="btn btn-sm btn-success btn-icon-split ml-2">
                    <span class="icon">
                        <i class="fa fa-file-excel"></i>
                    </span>
                    <span class="text">
                        Excel
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card-body border-bottom">
        <form method="get" action="<?= base_url('kebutuhan') ?>" class="form-inline">
            <div class="form-group mb-2 mr-2">
                <label for="jenis_filter" class="sr-only">Filter Jenis</label>
                <select name="jenis_filter" id="jenis_filter" class="form-control form-control-sm">
                    <option value="">-- Semua Jenis --</option>
                    <?php foreach ($jenis as $j) : ?>
                        <option value="<?= $j['id_jenis'] ?>" <?= ($selected_jenis == $j['id_jenis']) ? 'selected' : '' ?>>
                            <?= $j['nama_jenis'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mb-2 mr-2">
                <label for="tahun_filter" class="sr-only">Filter Tahun</label>
                <select name="tahun_filter" id="tahun_filter" class="form-control form-control-sm">
                    <option value="">-- Pilih Tahun --</option>
                    <?php foreach ($tahun_list as $tahun) : ?>
                        <option value="<?= $tahun['tahun'] ?>" <?= ($selected_tahun == $tahun['tahun']) ? 'selected' : '' ?>>
                            <?= $tahun['tahun'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-primary mb-2">
                <i class="fa fa-filter"></i> Filter
            </button>
            <a href="<?= base_url('kebutuhan') ?>" class="btn btn-sm btn-secondary mb-2 ml-1">
                <i class="fa fa-refresh"></i> Reset
            </a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th class="text-center">Stok<br><small>(Tersedia)</small></th>
                    <th class="text-center">Kebutuhan<br><small>(Dibutuhkan)</small></th>
                    <th class="text-center">Persentase<br><small>(Kebutuhan ÷ Stok)</small></th>
                    <th>Satuan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($kebutuhan) :
                    foreach ($kebutuhan as $k) :
                        // Tentukan warna berdasarkan persentase
                        $badge_color = 'badge-success'; // Hijau (0-50%)
                        if ($k['persentase'] > 50 && $k['persentase'] <= 75) {
                            $badge_color = 'badge-warning'; // Kuning (51-75%)
                        } elseif ($k['persentase'] > 75) {
                            $badge_color = 'badge-danger'; // Merah (>75%)
                        }
                        
                        // Tentukan icon berdasarkan persentase
                        $persentase_icon = 'fa-check-circle';
                        if ($k['persentase'] > 50) {
                            $persentase_icon = 'fa-exclamation-triangle';
                        }
                        if ($k['persentase'] > 75) {
                            $persentase_icon = 'fa-times-circle';
                        }
                        
                        // Hitung perbandingan
                        $stok_formatted = number_format($k['stok'], 0, ',', '.');
                        $kebutuhan_formatted = number_format($k['kebutuhan'], 0, ',', '.');
                        $perhitungan = "({$kebutuhan_formatted} ÷ {$stok_formatted}) × 100 = {$k['persentase_formatted']}";
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <span class="badge badge-light">
                                    <?= date('d/m/Y', strtotime($k['tanggal'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold"><?= $k['nama_barang']; ?></div>
                                <?php if (!empty($k['merk'])) : ?>
                                    <small class="text-muted">
                                        <i class="fa fa-tag"></i> <?= $k['merk']; ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <?= $k['nama_jenis']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-weight-bold <?= $k['stok'] <= 10 ? 'text-danger' : 'text-success' ?>">
                                    <?= $stok_formatted; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-weight-bold text-primary">
                                    <?= $kebutuhan_formatted; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="mr-2">
                                        <i class="fa <?= $persentase_icon ?> text-<?= $badge_color ?>"></i>
                                    </div>
                                    <div class="progress flex-grow-1" style="height: 25px; max-width: 200px;">
                                        <div class="progress-bar bg-<?= $badge_color ?> progress-bar-striped" 
                                             role="progressbar" 
                                             style="width: <?= min($k['persentase'], 100) ?>%" 
                                             aria-valuenow="<?= $k['persentase'] ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"
                                             data-toggle="tooltip"
                                             title="<?= $perhitungan ?>">
                                            <span class="font-weight-bold"><?= $k['persentase_formatted']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="fa fa-calculator"></i> <?= $perhitungan ?>
                                </small>
                            </td>
                            <td><?= $k['nama_satuan']; ?></td>
                            <td>
                                <?php if ($k['status'] == 1) : ?>
                                    <span class="badge badge-success">
                                        <i class="fa fa-check"></i> Aktif
                                    </span>
                                <?php else : ?>
                                    <span class="badge badge-secondary">
                                        <i class="fa fa-times"></i> Non-Aktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('kebutuhan/edit/') . $k['id_kebutuhan'] ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Edit"
                                       data-toggle="tooltip">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a onclick="return confirm('Yakin menghapus kebutuhan <?= $k['nama_barang'] ?>?')" 
                                       href="<?= base_url('kebutuhan/delete/') . $k['id_kebutuhan'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Hapus"
                                       data-toggle="tooltip">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-info btn-sm btn-detail"
                                            title="Detail Perhitungan"
                                            data-toggle="tooltip"
                                            data-id="<?= $k['id_kebutuhan'] ?>"
                                            data-barang="<?= $k['nama_barang'] ?>"
                                            data-stok="<?= $k['stok'] ?>"
                                            data-kebutuhan="<?= $k['kebutuhan'] ?>"
                                            data-persen="<?= $k['persentase_formatted'] ?>"
                                            data-perhitungan="<?= $perhitungan ?>"
                                            data-satuan="<?= $k['nama_satuan'] ?>"
                                            data-tanggal="<?= date('d/m/Y', strtotime($k['tanggal'])) ?>"
                                            data-warna="<?= $badge_color ?>">
                                        <i class="fa fa-info-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i> Tidak ada data kebutuhan untuk ditampilkan.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <?php if ($kebutuhan) : ?>
                    <tr class="table-primary">
                        <td colspan="4" class="text-right font-weight-bold">TOTAL:</td>
                        <td class="text-center font-weight-bold">
                            <?php 
                            $total_stok = array_sum(array_column($kebutuhan, 'stok'));
                            echo number_format($total_stok, 0, ',', '.');
                            ?>
                        </td>
                        <td class="text-center font-weight-bold">
                            <?php 
                            $total_kebutuhan = array_sum(array_column($kebutuhan, 'kebutuhan'));
                            echo number_format($total_kebutuhan, 0, ',', '.');
                            ?>
                        </td>
                        <td class="text-center font-weight-bold">
                            <?php 
                            $rata_persen = $total_stok > 0 ? ($total_kebutuhan / $total_stok) * 100 : 0;
                            echo number_format($rata_persen, 2, ',', '.') . '%';
                            ?>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>
    </div>
</div>

<!-- Modal Detail Perhitungan -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="fa fa-calculator"></i> Detail Perhitungan Persentase
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <h5 id="detail-nama" class="font-weight-bold"></h5>
                    <small class="text-muted" id="detail-tanggal"></small>
                </div>
                
                <table class="table table-bordered">
                    <tr>
                        <th width="40%" class="bg-light">Stok Tersedia</th>
                        <td width="60%" id="detail-stok"></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Jumlah Kebutuhan</th>
                        <td id="detail-kebutuhan"></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Rumus Perhitungan</th>
                        <td>
                            <code id="detail-rumus"></code>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Hasil Perhitungan</th>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1" style="height: 25px;">
                                    <div class="progress-bar" 
                                         id="detail-progress-bar"
                                         role="progressbar" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <span class="font-weight-bold" id="detail-persen"></span>
                                    </div>
                                </div>
                                <span class="badge ml-2" id="detail-badge"></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Interpretasi</th>
                        <td id="detail-interpretasi"></td>
                    </tr>
                </table>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fa fa-lightbulb"></i> Cara Perhitungan:</h6>
                    <p class="mb-0">Persentase dihitung dengan rumus: <strong>(Kebutuhan ÷ Stok) × 100</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.progress {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
}
.progress-bar {
    transition: width 0.6s ease;
    font-size: 0.8rem;
}
.badge-success { background-color: #28a745 !important; }
.badge-warning { background-color: #ffc107 !important; }
.badge-danger { background-color: #dc3545 !important; }
.btn-group .btn-sm {
    padding: 0.25rem 0.5rem;
    margin-right: 2px;
}
.table tfoot td {
    background-color: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Detail button click handler
    const detailButtons = document.querySelectorAll('.btn-detail');
    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const stok = parseInt(this.getAttribute('data-stok'));
            const kebutuhan = parseInt(this.getAttribute('data-kebutuhan'));
            const persentase = parseFloat(this.getAttribute('data-persen').replace('%', '').replace(',', '.'));
            
            // Hitung ulang persentase
            const persentaseHitung = stok > 0 ? ((kebutuhan / stok) * 100) : 0;
            
            // Set badge color
            const warna = this.getAttribute('data-warna');
            const badgeColor = warna.replace('bg-', '');
            
            // Tentukan interpretasi
            let interpretasi = 'Rendah (≤50%) - Kebutuhan kecil dari stok';
            if (persentaseHitung > 50 && persentaseHitung <= 75) {
                interpretasi = 'Sedang (51-75%) - Kebutuhan cukup besar';
            } else if (persentaseHitung > 75) {
                interpretasi = 'Tinggi (>75%) - Kebutuhan mendekati stok, perlu perhatian';
            }
            
            // Isi modal dengan data
            document.getElementById('detail-nama').textContent = this.getAttribute('data-barang');
            document.getElementById('detail-tanggal').textContent = 'Tanggal: ' + this.getAttribute('data-tanggal');
            document.getElementById('detail-stok').textContent = stok.toLocaleString('id-ID') + ' ' + this.getAttribute('data-satuan');
            document.getElementById('detail-kebutuhan').textContent = kebutuhan.toLocaleString('id-ID') + ' ' + this.getAttribute('data-satuan');
            document.getElementById('detail-rumus').textContent = 
                `(${kebutuhan.toLocaleString('id-ID')} ÷ ${stok.toLocaleString('id-ID')}) × 100 = ${persentaseHitung.toFixed(2)}%`;
            document.getElementById('detail-persen').textContent = persentaseHitung.toFixed(2) + '%';
            document.getElementById('detail-badge').className = 'badge badge-' + badgeColor;
            document.getElementById('detail-badge').textContent = persentaseHitung.toFixed(2) + '%';
            document.getElementById('detail-interpretasi').textContent = interpretasi;
            
            // Set progress bar
            const progressBar = document.getElementById('detail-progress-bar');
            progressBar.className = 'progress-bar bg-' + badgeColor;
            progressBar.style.width = Math.min(persentaseHitung, 100) + '%';
            
            // Tampilkan modal
            detailModal.show();
        });
    });
    
    // Auto submit filter on change
    const jenisFilter = document.getElementById('jenis_filter');
    const tahunFilter = document.getElementById('tahun_filter');
    
    if (jenisFilter) {
        jenisFilter.addEventListener('change', function() {
            if (this.value) {
                this.form.submit();
            }
        });
    }
    
    if (tahunFilter) {
        tahunFilter.addEventListener('change', function() {
            if (this.value) {
                this.form.submit();
            }
        });
    }
    
    // Sort table by percentage
    const table = document.getElementById('dataTable');
    if (table) {
        const tbody = table.querySelector('tbody');
        const persentaseHeader = table.querySelector('th:nth-child(7)');
        
        if (persentaseHeader && tbody.rows.length > 0) {
            const sortIcon = document.createElement('i');
            sortIcon.className = 'fa fa-sort ml-1';
            sortIcon.style.cursor = 'pointer';
            sortIcon.title = 'Klik untuk mengurutkan berdasarkan persentase';
            persentaseHeader.appendChild(sortIcon);
            
            let sortAscending = true;
            
            sortIcon.addEventListener('click', function() {
                const rows = Array.from(tbody.querySelectorAll('tr'));
                
                rows.sort((a, b) => {
                    // Ambil persentase dari progress bar
                    const progressA = a.querySelector('.progress-bar');
                    const progressB = b.querySelector('.progress-bar');
                    
                    if (!progressA || !progressB) return 0;
                    
                    const widthA = parseFloat(progressA.style.width.replace('%', ''));
                    const widthB = parseFloat(progressB.style.width.replace('%', ''));
                    
                    return sortAscending ? widthB - widthA : widthA - widthB;
                });
                
                // Reorder rows
                rows.forEach(row => tbody.appendChild(row));
                sortAscending = !sortAscending;
                
                // Update sort icon
                this.className = sortAscending ? 'fa fa-sort-desc ml-1' : 'fa fa-sort-asc ml-1';
            });
        }
    }
});
</script>