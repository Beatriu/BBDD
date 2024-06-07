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



<?php if ($tipus_inventari_desactivar !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.tipus_inventari_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tipus/inventari") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('tipus.tipus_inventari_model_text') ?><?php echo $tipus_inventari_desactivar['nom_tipus_inventari'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarTipusInventari/" . $tipus_inventari_desactivar['id_tipus_inventari']) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tipus/inventari") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="container p-0 overflow-hidden">

    <div class="row mt-5 justify-content-center">
        <div class="col-2 d-flex align-items-center">
            <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets') ?>">
                <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
            </a>
        </div>
        <div class="col-10 justify-content-left">
            <h1><?= lang('tipus.tipus_inventari') ?></h1>
        </div>
    </div>
    <div>
        <?php if ((session()->get('tipus_inventari_buit')) !== null) : ?>
            <div class="alert alert-warning alerta_esborrar" role="alert">
                <?= session()->get('tipus_inventari_buit') ?>
            </div>
        <?php endif; ?>
        <?php if ((session()->get('tipus_inventari_existeix')) !== null) : ?>
            <div class="alert alert-warning alerta_esborrar" role="alert">
                <?= session()->get('tipus_inventari_existeix') ?>
            </div>
        <?php endif; ?>
        <?php if ((session()->get('tipus_inventari_desactivat')) !== null) : ?>
            <div class="alert alert-danger alerta_esborrar" role="alert">
                <?= session()->get('tipus_inventari_desactivat') ?>
            </div>
        <?php endif; ?>
        <?php if ((session()->get('tipus_inventari_esborrat')) !== null) : ?>
            <div class="alert alert-danger alerta_esborrar" role="alert">
                <?= session()->get('tipus_inventari_esborrat') ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="row border mt-4 me-0 pe-0 ps-0">
        <div class="row form_header p-3 ms-0">
            
        </div>
    </div>

    <form method="POST" action="/tipus/inventari/afegir" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-9 mt-4">
                <input type="text" class="form-control" name="tipus_inventari" id="tipusInventari" placeholder="<?= lang('tipus.escriu_tipus_inventari') ?>" required>
            </div>
            <div class="col-3 mt-4 d">
                <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('tipus.afegir_tipus_inventari') ?></button>
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