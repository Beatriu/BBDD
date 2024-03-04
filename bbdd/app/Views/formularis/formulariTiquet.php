<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css/formulari.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
    <?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

    <form class="container" method="POST" action="">
        <div class="row mt-5 justify-content-center">
            <h1><?= lang('general_lang.create_tiquet') ?></h1>
        </div>
        <div class="row mt-3">
            <div class="col">
                <label class="" for="sNomContacteCentre"><?= lang('general_lang.name') ?></label>
                <input class="entrada" type="text" id="sNomContacteCentre" />
            </div>
            <div class="col">
                <label class="" for="sCorreuContacteCentre"><?= lang('general_lang.contact') ?></label>
                <input class="entrada" type="text" id="sCorreuContacteCentre" />
            </div>
        </div>
        <div class="row border mt-4">
            <div class="row form_header p-2">
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.equipment_code') ?>
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.type') ?>
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.problem') ?>
                </div>
                <div class="col d-flex align-items-center justify-content-end">
                    <button type="button" class="btn btn-success rounded-pill">Afegir</button>
                </div>
            </div>
        </div>
    
        <!--<div class="w-50 p-3" id="formulari">
            <div class="form-group">
                <label class="d-flex justify-content-center" for="sNomContacteCentre"><?= lang('general_lang.user') ?>:</label>
                <input class="entrada" type="text" id="sNomContacteCentre" />
            </div>
            <div class="form-group">
                <label class="d-flex justify-content-center" for="sCorreuContacteCentre"><?= lang('general_lang.password') ?>:</label>
                <input class="entrada" type="text" id="sCorreuContacteCentre" />
            </div>
            <br />
            <div class="d-flex justify-content-center">
                <button class="btn btn-outline-dark"><?= lang('crud.buttons.enter') ?></button>
            </div>
        </div>-->
    </form>

<?= $this->endSection('contingut'); ?>