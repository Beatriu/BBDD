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
        <!--Sidebar estàtic-->
        <div class="col-sm-auto px-0" id="sidebar">
        <ul class="nav flex-column">
                <?php if($uri == 'registreTiquet'):?>
                    <li class="nav-item" id="actiu" title="<?= lang("registre.dispositius_rebuts") ?> ">
                <?php else:?>
                    <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?> ">
                <?php endif;?>
                        <a href="/registreTiquet" class="nav-link py-3 px-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-hammer"></i>
                        </a>
                    </li>
                <?php if($uri == 'registreTiquet/emissor'):?>
                    <li class="nav-item" id="actiu" title="<?= lang("registre.dispositius_rebuts") ?>">
                <?php else:?>
                    <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                <?php endif;?>
                        <a href="/registreTiquet/emissor" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                    </li>
                
                    <li class="nav-item" title="<?= lang("registre.inventari") ?>">
                        <a href="#" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </a>
                    </li>
                <?php if($uri == 'registreAlumnes'): ?>
                    <li class="nav-item" id="actiu" title="<?= lang("registre.alumnes") ?>">
                <?php else: ?>
                    <li class="nav-item" title="<?= lang("registre.alumnes") ?>">
                <?php endif; ?>
                        <a href="/registreAlumnes" class="nav-link py-3 px-2" title="<?= lang("registre.alumnes") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-users"></i>
                        </a>
                    </li>
            </ul>
        </div>

        <!--Taula i títol-->
        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><?= lang("alumne.registre_alumnes") ?></h1>
                </div>
                <div>
                    <a href="<?= base_url("/afegir/alumne") ?>" class="btn" id="btn-create"><i class="fa-solid fa-circle-plus"></i> <?= lang("alumne.button_afegir_alumne") ?></a>
                </div>
            </div>
            <div>
                <?= $output ?>
            </div>
        </div>
    </div>
</div>
<script>
    function _open() {
        document.getElementById("mySidebar").style.display = "block";
        document.getElementById("mySidebar").style.backgroundColor = "#900000";
        document.getElementById("sidebar").style.display = "none";
    }

    function _close() {
        document.getElementById("mySidebar").style.display = "none";
        document.getElementById("sidebar").style.display = "block";
    }
</script>
<?= $this->endSection('contingut'); ?>