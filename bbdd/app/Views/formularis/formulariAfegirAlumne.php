<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('fontawesome' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'fontawesome.css') ?>" />
<script src="<?= base_url('js' . DIRECTORY_SEPARATOR . 'main_formulari_tiquet.js') ?>"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>
<?php if ($afegir_alumne_error != null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('alumne.alerta') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/alumnes/afegir") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body text-warning">
                    <p><?= lang($afegir_alumne_error) ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<form class="container" method="POST" action="<?= base_url('/alumnes/afegir') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row mt-5 justify-content-center">
        <div class="col-2 d-flex align-items-center">
            <a class="btn btn-dark rounded-pill" href="<?= base_url('/alumnes') ?>">
                <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
            </a>
        </div>
        <div class="col-10 justify-content-left">
            <h1><?= lang('alumne.formulari_alumne') ?></h1>
        </div>
    </div>

    <div class="row border mt-4 me-0 pe-0 ps-0">
        <div class="row form_header pt-2 pb-2 ps-3 pe-3 ms-0">
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col">
            <label for="correu_alumne" class="form-label"><?= lang('alumne.correu_alumne') ?> *</label>
            <input type="email" class="form-control" name = "correu_alumne" id="correu_alumne" placeholder="<?= lang('alumne.correu_alumne') ?>" required>
        </div>
        <?php if ($role == "admin_sstt" || $role == "desenvolupador") : ?>
            <div class="col">
                <label for="centre" class="form-label"><?= lang('alumne.centre') ?> *</label>
                <input class="form-control selector" name="centre" list="datalistOptionsCentres" id="centre" placeholder="<?= lang('alumne.centre') ?>" required>
                <datalist id="datalistOptionsCentres">
                    <?= $centres ?>
                </datalist>
            </div>
        <?php endif; ?>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="d-flex justify-content-center align-items-center">
            <button id="submit_afegir" type="submit" class="btn btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('alumne.save_alumne') ?></button>
        </div>
    </div>

</form>

<?= $this->endSection('contingut'); ?>