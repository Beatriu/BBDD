<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<header class="d-flex justify-content-between" style="background-color: #333333;">
    <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />

    <a><img class="imatge" src="<?= base_url(lang('general_lang.banderilla')) ?>" /></a>

</header>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>
<h1>Registre de dispositius</h1>
    <?= $output?>
<?= $this->endSection('contingut'); ?>

