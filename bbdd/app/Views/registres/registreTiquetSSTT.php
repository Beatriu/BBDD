<?= $this->extend('layouts/professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css/taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-auto px-0" id="sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item" id="actiu">
                        <!--TODO: fer la vista amb els if de depenent de el paràmetre que arribi per la ruta es vegui activada la classe de un a o un altre.-->
                        <?//php if($type == "inventari"): ?>
                        <a href="#" class="nav-link py-3 px-2"  title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link py-3 px-2" title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link py-3 px-2" title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-users"></i>
                        </a>
                    </li>
                </ul>
            
        </div>
        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <div class="d-flex justify-content-center">
                <h1>Registre de dispositius</h1>
            </div>
            <div >
                <?= $output ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('contingut'); ?>