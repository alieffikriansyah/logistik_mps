<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    <i class="fa fa-chart-bar"></i> Laporan Kebutuhan per Jenis Barang
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

    <!-- Filter Section -->
    <div class="card-body border-bottom">
        <form method="get" action="<?= base_url('kebutuhan/laporan') ?>" class="form-inline">
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
                    <option value="">-- Semua Tahun --</option>
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
            <a href="<?= base_url('kebutuhan/laporan') ?>" class="btn btn-sm btn-secondary mb-2 ml-1">
                <i class="fa fa-refresh"></i> Reset
            </a>
        </form>
    </div>

    <div class="card-body">
        <?php if ($laporan) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="laporanTable">
                    <thead class="bg-light">
                        <tr>
                            <th>No.</th>
                            <th>Jenis Barang</th>
                            <th>Jumlah Barang</th>
                            <th>Total Kebutuhan</th>
                            <th>Rata-rata per Barang</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_kebutuhan_keseluruhan = 0;
                        
                        // Hitung total kebutuhan keseluruhan
                        foreach ($laporan as $l) {
                            $total_kebutuhan_keseluruhan += $l['total_kebutuhan'];
                        }
                        
                        foreach ($laporan as $l) :
                            $persentase = ($total_kebutuhan_keseluruhan > 0) ? 
                                ($l['total_kebutuhan'] / $total_kebutuhan_keseluruhan) * 100 : 0;
                            $rata_rata = ($l['total_barang'] > 0) ? 
                                $l['total_kebutuhan'] / $l['total_barang'] : 0;
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <span class="font-weight-bold"><?= $l['nama_jenis']; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info"><?= $l['total_barang']; ?> Barang</span>
                                </td>
                                <td class="text-right">
                                    <span class="font-weight-bold text-primary">
                                        <?= number_format($l['total_kebutuhan'], 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <?= number_format($rata_rata, 2, ',', '.'); ?>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?= $persentase; ?>%" 
                                             aria-valuenow="<?= $persentase; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= number_format($persentase, 1); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-primary">
                            <td colspan="2" class="text-right font-weight-bold">TOTAL</td>
                            <td class="text-center font-weight-bold">
                                <?php 
                                $total_barang = array_sum(array_column($laporan, 'total_barang'));
                                echo $total_barang . ' Barang';
                                ?>
                            </td>
                            <td class="text-right font-weight-bold">
                                <?= number_format($total_kebutuhan_keseluruhan, 0, ',', '.'); ?>
                            </td>
                            <td colspan="2" class="text-center font-weight-bold">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Chart Section -->
            <div class="mt-4">
                <h5 class="font-weight-bold text-primary mb-3">
                    <i class="fa fa-chart-pie"></i> Grafik Distribusi Kebutuhan per Jenis
                </h5>
                <div class="row">
                    <div class="col-lg-8">
                        <canvas id="kebutuhanChart" height="150"></canvas>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold">Ringkasan</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Periode:</strong> Tahun <?= $selected_tahun ?: date('Y') ?></p>
                                <p><strong>Total Jenis:</strong> <?= count($laporan) ?></p>
                                <p><strong>Total Barang:</strong> <?= $total_barang ?></p>
                                <p><strong>Total Kebutuhan:</strong> <?= number_format($total_kebutuhan_keseluruhan, 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> Tidak ada data laporan untuk ditampilkan.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($laporan) : ?>
        // Prepare data for chart
        const labels = <?= json_encode(array_column($laporan, 'nama_jenis')) ?>;
        const data = <?= json_encode(array_column($laporan, 'total_kebutuhan')) ?>;
        
        // Generate random colors
        const backgroundColors = labels.map(() => {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            return `rgba(${r}, ${g}, ${b}, 0.7)`;
        });
        
        const borderColors = backgroundColors.map(color => color.replace('0.7', '1'));
        
        // Create chart
        const ctx = document.getElementById('kebutuhanChart').getContext('2d');
        const kebutuhanChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                }
            }
        });
    <?php endif; ?>
    
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
});
</script>