<?= $this->extend('layouts/professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'style.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>

<div class="container p-0 overflow-hidden">

    <div class="row mt-5 justify-content-center">
        <div class="col-2 d-flex align-items-center">
            <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets/' . $id_tiquet) ?>">
                <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
            </a>
        </div>
        <div class="col-10 justify-content-left">
            <h1><?= lang('intervencio.inventari_assignat') ?></h1>
        </div>
    </div>
    <div>
        <?php if ((session()->get('assignarInventari')) !== null) : ?>
            <div class="alert alert-success alerta_esborrar" role="alert">
                <?= session()->get('assignarInventari') ?>
            </div>
        <?php endif; ?>
        <?php if ((session()->get('desassingarInventari')) !== null) : ?>
            <div class="alert alert-danger alerta_esborrar" role="alert">
                <?= session()->get('desassingarInventari') ?>
            </div>
        <?php endif; ?>
        <?php if ((session()->get('error_tipus_inventari')) !== null) : ?>
            <div class="alert alert-danger alerta_esborrar" role="alert">
                <?= session()->get('error_tipus_inventari') ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="row border mt-4 me-0 pe-0 ps-0">
        <div class="row form_header p-3 ms-0">
            <span><?= lang('intervencio.intervencio_assignada') ?> <?= $id_intervencio ?></span>
        </div>
    </div>

    <form method="POST" action="<?= base_url('/tiquets/' . $id_tiquet . '/assignar/' . $id_intervencio . '/noeditar') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-10 mt-4">
                <input class="form-control selector" name="inventari" list="datalistOptionsInventari" id="intervencioDataListInventari" placeholder="<?= lang('intervencio.selecciona_inventari') ?>">
                <datalist id="datalistOptionsInventari">
                    <?= $inventari_list ?>
                </datalist>
            </div>
            <div class="col-2 mt-4 d">
                <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('intervencio.button_assignar_inventari') ?></button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <?= $output ?>
        </div>
    </div>


</div>
<script>
</script>
<?= $this->endSection('contingut'); ?>