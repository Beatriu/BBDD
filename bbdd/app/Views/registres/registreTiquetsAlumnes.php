<?= $this->extend('layouts' . DIRECTORY_SEPARATOR . 'alumnes'); ?>
<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts' . DIRECTORY_SEPARATOR . 'header.php'); ?>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>
<div class="container-fluid">
    <div class="row">
        <!--Sidebar estàtic-->
        <div class="col-sm-auto px-0" id="sidebar">
            <ul class="nav flex-column">
                
            </ul>
        </div>
        <!--Taula i títol-->
        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                        <h1><?= lang("registre.titol_dispositius_sstt") ?></h1>
                </div>
                <div>
                   <!-- <button onclick="_open()" class="btn" id="btn-filter"><i class="fa-solid fa-filter"></i> <?//= lang("registre.buttons.filter") ?></button>-->
                    <a href="<?= base_url("/formulariTiquet") ?>" class="btn" id="btn-create"><i class="fa-solid fa-circle-plus"></i> <?= lang("registre.buttons.create") ?></a>
                </div>
            </div>
            <div>
                <?php if($error != null) { echo lang( $error ); } ?>
                <?= $output ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('contingut'); ?>