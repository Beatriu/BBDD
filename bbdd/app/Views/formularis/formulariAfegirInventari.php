<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'fontawesome.css') ?>"/>
    <script src="<?= base_url('js' . DIRECTORY_SEPARATOR . 'main_formulari_tiquet.js') ?>"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
    <?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

    <div class="container">
            <form method="POST" action="<?= base_url('/inventari/afegir') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row mt-5 justify-content-center">
                    <div class="col-2 d-flex align-items-center">
                        <a class="btn btn-dark rounded-pill" href="<?= base_url('/inventari') ?>">
                            <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                        </a>
                    </div>
                    <div class="col-10 justify-content-left">
                        <h1><?= lang('inventari.formulari_afegir_inventari') ?></h1>
                    </div>
                </div>

                <div class="row border mt-4 me-0 pe-0 ps-0">
                    <div class="row form_header pt-2 pb-2 ps-3 pe-3 ms-0">
                    </div>
                </div>

                <div class="row mt-3 mb-3">
                    <div class="col">
                        <label for="quantitat" class="form-label"><?= lang('inventari.quantitat') ?> *</label>
                        <input type="number" min="1" class="form-control" name = "quantitat" id="quantitat" placeholder="<?= lang('inventari.quantitat') ?>" value = "1" required>
                    </div>

                    <div class="col">
                        <label for="tipus_inventari" class="form-label"><?= lang('inventari.tipus_inventari') ?> *</label>
                        <input class="form-control selector" name = "tipus_inventari" list="datalistOptionsTipusInventari" id="tipus_inventari" placeholder="<?= lang('inventari.tipus_inventari') ?>" required>
                        <datalist id="datalistOptionsTipusInventari">
                            <?= $tipus_inventari ?>
                        </datalist>
                    </div>

                    <div class="col">
                        <label for="preu" class="form-label"><?= lang('inventari.preu') ?> *</label>
                        <input type="number" step="any" min="0.00" class="form-control" name = "preu" id="preu" placeholder="<?= lang('inventari.preu') ?>" value = "0" required>
                    </div>

                </div>

                <div class="mb-3">
                    <label for="descripcio_inventari" class="form-label"><?= lang('inventari.descripcio_inventari_limitada') ?></label>
                    <textarea maxlength="512" class="form-control" id="descripcio_inventari" name="descripcio_inventari" rows="3" placeholder="<?= lang('inventari.descripcio_inventari_limitada') ?>"></textarea>
                </div>

                <?php if ($role == "admin_sstt" || $role == "desenvolupador"): ?>
                    <div class="mb-3">
                        <div class="col">
                            <label for="codi_centre" class="form-label"><?= lang('inventari.codi_centre') ?> *</label>
                            <input class="form-control selector" name = "codi_centre" list="datalistCodiCentre" id="codi_centre" placeholder="<?= lang('inventari.codi_centre') ?>"  required>
                            <datalist id="datalistCodiCentre">
                                <?= $centres ?>
                            </datalist>
                        </div>
                    </div>
                <?php endif; ?>

                <?= validation_list_errors() ?>

                <div class="row justify-content-center mt-4">
                    <div class="d-flex justify-content-center align-items-center">
                        <button id="submit_afegir" type="submit" class="btn btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('inventari.save_inventari') ?></button>
                    </div>
                </div>

            </form>
    </div>

<?= $this->endSection('contingut'); ?>