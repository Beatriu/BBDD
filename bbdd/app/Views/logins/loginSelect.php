<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<header class="d-flex justify-content-between" style="background-color: #333333;">
    <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />

    <a><img class="imatge" src="<?= base_url(lang('general_lang.banderilla')) ?>" /></a>

</header>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>


<form class="d-flex align-items-center justify-content-center">
    <div class="w-25 p-3" id="formulari">
        <div class="contenidor form-group d-flex flex-column">
            <div class="d-flex justify-content-center">
                <p class="titol h6">Institut</p>
            </div>
            <div class="d-flex justify-content-center">
                <!-- TODO: Canviar selector per un datalist -->
                <select class="selector" name="" id="">
                    <option>Caparrella</option>
                    <option>Tremp</option>
                </select>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-outline-dark"><?= lang('crud.buttons.enter') ?></button>
            </div>
        </div>
        <br />
    </div>
</form>
<?= $this->endSection('contingut'); ?>