<?= $this->extend('layouts/professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<? //= base_url('css/login.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<header class="d-flex justify-content-between" style="background-color: #333333;">
    <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />

    <a><img class="imatge" src="<?= base_url(lang('general_lang.banderilla')) ?>" /></a>
    <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Usuari
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li> <a class="dropdown-item" href="config.php"><i class="fa-solid fa-wrench"></i> 2FA</a> </li>
            <li> <a href="<?= base_url("$locale/logout") ?>" class="dropdown-item" href="close.php"><i class="fa-solid fa-right-from-bracket"></i> <?= lang('header.tancar_sessio') ?></a> </li>
        </ul>
    </div>
</header>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>
<h1>Registre de dispositius</h1>
<div class="d-flex justify-content-end">
    <?= $output ?>
</div>

<?= $this->endSection('contingut'); ?>