<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css/header.css') ?>">

    <link rel="stylesheet" href="<?= base_url('fontawesome/css/fontawesome.css') ?>"/>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
    <?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

    <div class="col-2">

        <img src="" alt="">

        <div class="card m-md-4">
            <div class="bg-dark text-white p-1">
                <h2><?= lang('general_lang.dades_tiquet.dades_titol') ?></h2>
            </div>
            <ul class="w-17 list-inline border-danger fs-5 p-1">
                <li><?= lang('general_lang.dades_tiquet.dades_codi') ?> </li>
                <li><?= lang('general_lang.dades_tiquet.dades_tipus') ?> </li>
                <li><?= lang('general_lang.dades_tiquet.dades_estat') ?> </li>
            </ul>
        </div>

    </div>
        
    <div class="border col-2 m-4">
        
        <div class="card m-md-4">TITOL TIQUET I LLISTAT INTERVENCIONS</div>

    </div>

<?= $this->endSection('contingut'); ?>