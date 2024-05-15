<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css/formulari.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/fontawesome.css') ?>"/>
    <script src="<?= base_url('js/main_formulari_tiquet.js') ?>"></script>
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

        <div class="row border mt-4 me-0 pe-0 ps-0">
            <div class="row form_header p-3 ms-0">
                <span><?= lang('intervencio.intervencio_editar') ?></span>
            </div>
        </div>

        <div class="row mt-3 mb-3">
            <div class="col">
                <label for="id_intervencio" class="form-label"><?= lang('intervencio.id_intervencio') ?> *</label>
                <input class="form-control" name = "id_intervencio" id="id_intervencio" value="<?= $intervencio['id_intervencio'] ?>" disabled>
            </div>
            <div class="col">
                <label for="tipus_intervencio" class="form-label"><?= lang('intervencio.tipus_intervencio') ?> *</label>
                <input class="form-control selector" name = "tipus_intervencio" list="datalistOptionsTipusIntervencio" id="tipus_intervencio" placeholder="<?= lang('intervencio.tipus_intervencio') ?>" value="<?= $selected_intervencio ?>" required>
                <datalist id="datalistOptionsTipusIntervencio">
                    <?=$tipus_intervencio?>
                </datalist>
                
            </div>
            <div class="col">
            <label for="curs" class="form-label"><?= lang('intervencio.curs') ?> *</label>
                <input class="form-control selector" name = "curs" list="datalistOptionsCursos" id="curs" placeholder="<?= lang('intervencio.curs') ?>" value="<?= $selected_curs ?>" required>
                <datalist id="datalistOptionsCursos">
                    <?=$cursos?>
                </datalist>
            </div>
        </div>

        <div class="mb-3">
            <label for="descripcio_intervencio" class="form-label"><?= lang('intervencio.descripcio_intervencio') ?></label>
            <textarea maxlength="512" class="form-control" id="descripcio_intervencio" name="descripcio_intervencio" rows="3"><?= $intervencio['descripcio_intervencio'] ?></textarea>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="d-flex justify-content-center align-items-center">
                <button id="submit_afegir" type="submit" class="btn btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('intervencio.save_intervencio') ?></button>
            </div>
        </div>

    </form>

<?= $this->endSection('contingut'); ?>