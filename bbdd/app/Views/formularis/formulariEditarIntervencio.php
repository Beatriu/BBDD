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

<form class="container" method="POST" action="<?= base_url('/editar/intervencio') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row mt-5 justify-content-center">
        <div class="col-2 d-flex align-items-center">
            <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets/' . $id_tiquet) ?>">
                <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
            </a>
        </div>

        <div class="col-10 justify-content-left">
            <h1><?= lang('intervencio.formulari_editar_intervencio') ?></h1>
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
        <div class="row form_header pt-2 pb-2 ps-3 pe-3 ms-0">
            <span><?= lang('intervencio.intervencio_editar') ?></span>
        </div>
    </div>

    <div class="row mt-3 mb-3">

        <div class="col-6">
            <div class="row">
                <label for="id_intervencio" class="form-label"><?= lang('intervencio.id_intervencio') ?> *</label>
                <input class="form-control" name="id_intervencio" id="id_intervencio" value="<?= $intervencio['id_intervencio'] ?>" disabled>
            </div>
            <div class="row mt-2">
                <label for="tipus_intervencio" class="form-label"><?= lang('intervencio.tipus_intervencio') ?> *</label>
                <input class="form-control selector" name="tipus_intervencio" list="datalistOptionsTipusIntervencio" id="tipus_intervencio" placeholder="<?= lang('intervencio.tipus_intervencio') ?>" value="<?= $selected_intervencio ?>" required>
                <datalist id="datalistOptionsTipusIntervencio">
                    <?= $tipus_intervencio ?>
                </datalist>
            </div>
            <div class="row mt-2">
                <label for="curs" class="form-label"><?= lang('intervencio.curs') ?> *</label>
                <input class="form-control selector" name="curs" list="datalistOptionsCursos" id="curs" placeholder="<?= lang('intervencio.curs') ?>" value="<?= $selected_curs ?>" required>
                <datalist id="datalistOptionsCursos">
                    <?= $cursos ?>
                </datalist>
            </div>
            <div class="row mt-2">
                <label for="descripcio_intervencio" class="form-label"><?= lang('intervencio.descripcio_intervencio') ?></label>
                <textarea maxlength="512" class="form-control" id="descripcio_intervencio" name="descripcio_intervencio" rows="3"><?= $intervencio['descripcio_intervencio'] ?></textarea>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="d-flex justify-content-center align-items-center">
                    <button id="submit_afegir" type="submit" class="btn btn-primary btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('intervencio.editar_intervencio') ?></button>
                </div>
            </div>
        </div>

    </form>

        <div class="col-6">
            <form method="POST" action="<?= base_url('/tiquets/' . $id_tiquet . '/assignar/' . $intervencio['id_intervencio']) . '/editar' ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-8 mt-1">
                        <label for="intervencioDataListInventari" class="form-label mb-1"><?= lang('intervencio.button_assignar_inventari') ?></label>
                        <input class="form-control selector" name="inventari" list="datalistOptionsInventari" id="intervencioDataListInventari" placeholder="<?= lang('intervencio.selecciona_inventari') ?>">
                        <datalist id="datalistOptionsInventari">
                            <?= $inventari_list ?>
                        </datalist>
                    </div>
                    <div class="col-4 mt-4 d">
                        <button type="submit" class="btn btn-success rounded-pill mt-2"><i class="fa-solid fa-plus"></i> <?= lang('intervencio.button_assignar_inventari') ?></button>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col">
                    <?= $output ?>
                </div>
            </div>
        </div>

    </div>







<?= $this->endSection('contingut'); ?>