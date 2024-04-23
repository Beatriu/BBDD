<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<header class="d-flex justify-content-between" style="background-color: #333333;">
    <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />

    <a class="me-3" href="<?= base_url('/canviLanguage') ?>"><img class="ms-auto imatge" src="<?= base_url(lang('general_lang.banderilla')) ?>" /></a>

</header>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>


<form class="d-flex align-items-center justify-content-center" method="POST" action="<?= base_url('/loginSelect') ?>">
    <?= csrf_field() ?>
    <div class="w-25 p-3" id="formulari">
        <div class="contenidor form-group d-flex flex-column">
            <div class="d-flex justify-content-center">
                <p class="titol h6">Institut</p>
            </div>
            <div class="d-flex justify-content-center">
                <input class="form-control selector" name = "centre_seleccionat" list="datalistOptions" id="institutsDataList" placeholder="<?= lang('general_lang.centres_datalist') ?>">
                <datalist id="datalistOptions">
                    <?=$centres?>
                </datalist>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-outline-dark"><?= lang('crud.buttons.enter') ?></button>
            </div>
        </div>
        <br />
    </div>
</form>
<?= $this->endSection('contingut'); ?>