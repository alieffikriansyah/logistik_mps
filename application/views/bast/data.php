<div class="row justify-content-center">
    <div class="col">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Data BAST
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('bast/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span class="text">
                                Tambah BAST
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NO BAST</th>
                                <th>Tanggal</th>
                                <th>Dibuat Oleh</th>
                                <th>Total Items</th>
                                <th>Unit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($bast as $row) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <strong>
                                        <?= formatBastId(
                                            $row['id_bast'], 
                                            $row['nama_jenis'], 
                                            $row['unit'], 
                                            $row['tanggal']
                                        ) ?>
                                    </strong>
                                </td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td><?= $row['nama_user'] ?></td>
                                <td>
                                    <span class="badge badge-info"><?= $row['total_items'] ?> item</span>
                                </td>
                                 <td>
                                    <?= $row['unit'] ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('bast/detail/') . $row['id_bast'] ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                                    <a href="<?= base_url('bast/print_bast/') . $row['id_bast'] ?>" target="_blank" class="btn btn-sm btn-success">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                    <a href="<?= base_url('bast/delete/') . $row['id_bast'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus BAST?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>