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

<!-- Inici de formulari -->
<form class="container" method="POST" action="<?= base_url('/centres/afegir') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <!-- Títol i botó de tornar -->
    <div class="row mt-5 justify-content-center">
        <div class="col-2 d-flex align-items-center">
            <a class="btn btn-dark rounded-pill" href="<?= base_url('/centres') ?>">
                <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
            </a>
        </div>
        <div class="col-10 justify-content-left">
            <h1><?= lang('centre.formulari_centre') ?></h1>
        </div>
    </div>
    <!-- Alertes -->
    <div>
    <?php if ((session()->get('afegirCentre')) !== null) : ?>
        <div class="alert alert-danger alerta_esborrar" role="alert">
            <?= session()->get('afegirCentre') ?>
        </div>
    <?php endif; ?>
    <?php if ((session()->get('afegirCentre_requerits')) !== null) : ?>
        <div class="alert alert-danger alerta_esborrar" role="alert">
            <?= session()->get('afegirCentre_requerits') ?>
        </div>
    <?php endif; ?>
    </div>
    <!-- Linia negra -->
    <div class="row border mt-4 me-0 pe-0 ps-0">
        <div class="row form_header pt-2 pb-2 ps-3 pe-3 ms-0">
        </div>
    </div>
    <!--Zona inputs-->
    <div class="row mt-3 mb-3">
        <!--Codi Centre-->
        <div class="col">
            <label for="codi_centre" class="form-label"><?= lang('centre.codi_centre') ?> *</label>
            <input type="text" class="form-control" name="codi_centre" id="codi_centre" placeholder="<?= lang('centre.codi_centre') ?>" required>
        </div>
        <!-- Nom centre -->
        <div class="col">
            <label for="nom_centre" class="form-label"><?= lang('centre.nom_centre') ?> *</label>
            <input type="text" class="form-control" name="nom_centre" id="nom_centre" placeholder="<?= lang('centre.nom_centre') ?>" required>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <!-- Actiu -->
        <div class="col">
            <fieldset>
                <legend style="font-size: 16px;"><?= lang('centre.actiu_legend') ?> *</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="centre_actiu" id="centre_actiu" value='actiu' required>
                    <label class="form-check-label" for="centre_actiu">
                        <?= lang('centre.centre_actiu_radio') ?>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="centre_actiu" id="centre_no_actiu" value='no_actiu' required>
                    <label class="form-check-label" for="centre_no_actiu">
                        <?= lang('centre.centre_innactiu_radio') ?>
                    </label>
                </div>
            </fieldset>
        </div>
        <!-- Taller -->
        <div class="col">
            <fieldset>
                <legend style="font-size: 16px;"><?= lang('centre.taller_legend') ?> *</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="centre_taller" id="centre_taller" value='taller' required>
                    <label class="form-check-label" for="centre_taller">
                        <?= lang('centre.centre_taller_radio') ?>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="centre_taller" id="centre_no_taller" value='no_taller'>
                    <label class="form-check-label" for="centre_no_taller">
                        <?= lang('centre.centre_no_taller_radio') ?>
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <!-- Telefon -->
        <div class="col">
            <label for="telefon_centre" class="form-label"><?= lang('centre.telefon_centre') ?></label>
            <input type="tel" class="form-control" name="telefon_centre" id="telefon_centre" placeholder="<?= lang('centre.telefon_centre') ?>">
        </div>
        <!-- Adreça -->
        <div class="col">
            <label for="adreca" class="form-label"><?= lang('centre.adreca') ?></label>
            <input type="text" class="form-control" name="adreca" id="adreca" placeholder="<?= lang('centre.adreca') ?>">
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <!-- Nom persona de contacte -->
        <div class="col">
            <label for="nom_persona_de_contacte" class="form-label"><?= lang('centre.nom_persona_de_contacte') ?></label>
            <input type="text" class="form-control" name="nom_persona_de_contacte" id="nom_persona_de_contacte" placeholder="<?= lang('centre.nom_persona_de_contacte') ?>">
        </div>
        <!-- Correu persona de contacte -->
        <div class="col">
            <label for="correu_persona_contacte" class="form-label"><?= lang('centre.nom_correu_persona_contacte_centre') ?></label>
            <input type="email" class="form-control" name="correu_persona_contacte" id="correu_persona_contacte" placeholder="<?= lang('centre.nom_correu_persona_contacte_centre') ?>">
        </div>
    </div>
    <!-- Població -->
    <div class="row mt-3 mb-3">
        <div class="col">
            <label for="centre" class="form-label"><?= lang('centre.nom_poblacio') ?> *</label>
            <input class="form-control selector" name="nom_poblacio" list="datalistOptionsCentres" id="nom_poblacio" placeholder="<?= lang('centre.nom_poblacio') ?>" required>
            <datalist id="datalistOptionsCentres">
                <?= $poblacions
                ?>
            </datalist>
        </div>

        <!-- login -->
        <div class="col">
            <label for="login_centre" class="form-label"><?= lang('centre.login_centre') ?> *</label>
            <input type="email" class="form-control" name="login_centre" id="login_centre" placeholder="<?= lang('centre.login_centre') ?>">
        </div>
    </div>


    <!-- Botó afegir alumne -->
    <div class="row justify-content-center mt-4">
        <div class="d-flex justify-content-center align-items-center">
            <button id="submit_afegir" type="submit" class="btn btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('centre.buttons.create') ?></button>
        </div>
    </div>
</form>

<?= $this->endSection('contingut'); ?>