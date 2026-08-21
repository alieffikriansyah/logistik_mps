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
                    <th>Barang</th>
                    <th>Barang Baru</th>
                    <th>Jenis</th>
                    <th>Satuan</th>
                    <th>Stok Diminta</th>
                    <th>Link</th>
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
                ?>
                        <tr class="text-center">
                            <td><?= $no++; ?></td>
                            <td>MR<?= str_pad($mr['id_mr'], 4, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d M Y', strtotime($mr['tanggal'])); ?></td>
                            <td><?= $mr['nama_barang'] ?? '-'; ?></td>
                            <td><?= $mr['barang_minta'] ?? '-'; ?></td>
                            <td><?= $mr['nama_jenis'] ?? '-'; ?></td>
                            <td><?= $mr['nama_satuan'] ?? '-'; ?></td>
                            <td><?= $mr['stok']; ?></td>
                            <td>
                                <?php if (!empty($mr['link_rekomendasi'])) : ?>
                                    <a href="<?= $mr['link_rekomendasi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-external-link"></i> Link
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $mr['nama'] ?? '-'; ?></td>
                             <td><?= $mr['role'] ?? '-'; ?></td>
                            <td><?= $mr['status_label']; ?></td>

                           <?php if ($user_id == '1' || $user_id == '17' || $user_id == '18' || $user_id == '19') : ?>
                                <td>
                                    <a href="<?= base_url('material_request/edit/') . $mr['id_mr'] ?>" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('material_request/delete/') . $mr['id_mr'] ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>

                                    <!-- Dropdown untuk update realisasi -->
                                    <!-- <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">
                                            Ubah Status
                                        </button>
                                        <div class="dropdown-menu">
                                            <form action="<?= base_url('material_request/update_realisasi/' . $mr['id_mr']); ?>" method="post">
                                                <button type="submit" name="realisasi" value="0" class="dropdown-item <?= $mr['realisasi'] == 0 ? 'active' : ''; ?>">Baru</button>
                                                <button type="submit" name="realisasi" value="1" class="dropdown-item <?= $mr['realisasi'] == 1 ? 'active' : ''; ?>">Proses</button>
                                                <button type="submit" name="realisasi" value="2" class="dropdown-item <?= $mr['realisasi'] == 2 ? 'active' : ''; ?>">Tolak</button>
                                                <button type="submit" name="realisasi" value="3" class="dropdown-item <?= $mr['realisasi'] == 3 ? 'active' : ''; ?>">Selesai</button>
                                            </form>
                                        </div>
                                    </div> -->
                                </td>
                            <?php endif; ?>
                        </tr>
                <?php
                    endforeach;
                else :
                    echo '<tr><td colspan="' . ($user_id == '1' ? '11' : '10') . '" class="text-center">Data Kosong</td></tr>';
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>