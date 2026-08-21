<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form <?= $title; ?>
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('user') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open(); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="username">Username</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('username'); ?>" type="text" id="username" name="username" class="form-control" placeholder="Username">
                        <?= form_error('username', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="password">Password</label>
                    <div class="col-md-6">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <?= form_error('password', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="password2">Konfirmasi Password</label>
                    <div class="col-md-6">
                        <input type="password" id="password2" name="password2" class="form-control" placeholder="Konfirmasi Password">
                        <?= form_error('password2', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <hr>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('nama'); ?>" type="text" id="nama" name="nama" class="form-control" placeholder="Nama">
                        <?= form_error('nama', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="email">Email</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('email'); ?>" type="text" id="email" name="email" class="form-control" placeholder="Email">
                        <?= form_error('email', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="no_telp">Nomor Telepon</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('no_telp'); ?>" type="text" id="no_telp" name="no_telp" class="form-control" placeholder="Nomor Telepon">
                        <?= form_error('no_telp', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="role">Role</label>
                    <div class="col-md-6">
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'admin'); ?> value="admin" type="radio" id="admin" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="admin">Admin</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'gudang'); ?> value="gudang" type="radio" id="gudang" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="gudang">Gudang</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'hvmv'); ?> value="hvmv" type="radio" id="hvmv" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="hvmv">HVMV</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'ps1'); ?> value="ps1" type="radio" id="ps1" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="ps1">PS 1</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'ps2'); ?> value="ps2" type="radio" id="ps2" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="ps2">PS 2</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'ps3'); ?> value="ps3" type="radio" id="ps3" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="ps3">PS 3</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'ep'); ?> value="ep" type="radio" id="ep" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="ep">EP</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'en'); ?> value="en" type="radio" id="en" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="en">EN</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'nva'); ?> value="nva" type="radio" id="nva" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="nva">NVA</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'sva'); ?> value="sva" type="radio" id="sva" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="sva">SVA</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'eu'); ?> value="eu" type="radio" id="eu" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="eu">EU</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'ups'); ?> value="ups" type="radio" id="ups" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="ups">UPS</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 't1'); ?> value="t1" type="radio" id="t1" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="t1">T1</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 't2'); ?> value="t2" type="radio" id="t2" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="t2">T2</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 't3'); ?> value="t3" type="radio" id="t3" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="t3">T3</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'ntes'); ?> value="ntes" type="radio" id="ntes" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="ntes">NTES</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'ntes'); ?> value="office_mps" type="radio" id="office_mps" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="office_mps">OFFICE MPS</label>
                        </div>
                        <?= form_error('role', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <br>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            Reset
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>