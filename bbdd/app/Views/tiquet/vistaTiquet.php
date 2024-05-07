<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css/header.css') ?>">

    <link rel="stylesheet" href="<?= base_url('fontawesome/css/fontawesome.css') ?>"/>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
    <?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

    <div class="w-25 border">25%

        <img src="" alt="">

        <div class="w-17 border-right-1">
            <h2><?= lang('general_lang.dades_tiquet.dades_titol') ?></h2>
            <ul class="w-17 border-danger ">
                <li><?= lang('general_lang.dades_tiquet.dades_codi') ?> </li>
                <li><?= lang('general_lang.dades_tiquet.dades_tipus') ?> </li>
                <li><?= lang('general_lang.dades_tiquet.dades_estat') ?> </li>
            </ul>
        </div>

        

    </div>
        
    <div class="w-75 border">75%</div>

<?= $this->endSection('contingut'); ?>