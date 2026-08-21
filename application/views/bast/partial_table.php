<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>ID BAST</th>
            <th>Tanggal</th>
            <th>Dibuat Oleh</th>
            <th>Total Items</th>
            <th>Unit</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        if (!empty($bast)) :
            foreach ($bast as $row) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><strong>BAST<?= str_pad($row['id_bast'], 4, '0', STR_PAD_LEFT) ?></strong></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                <td><?= $row['nama_user'] ?></td>
                <td>
                    <span class="badge badge-info"><?= $row['total_items'] ?> item</span>
                </td>
                <td>
                    <span class="badge badge-secondary"><?= strtoupper($row['unit']) ?></span>
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
        <?php 
            endforeach;
        else : ?>
            <tr>
                <td colspan="7" class="text-center">Data tidak ditemukan</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>