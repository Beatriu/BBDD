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
<!--TODO: Arreglar language del modal-->
<?php if ($checkPopUp !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vols eliminar aquest tiquet?</h5>
                    <a href="<?= base_url("/registreTiquet") ?>">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <p>El que està a punt de succeir es que eliminaras el tiquet seleccionat <?php echo session()->getFlashdata('tiquet')["codi_equip"]; ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarTiquet/" . $checkPopUp) ?>" type="button" class="btn btn-danger">Eliminar</a>
                    <a href="<?= base_url("/registreTiquet") ?>" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel·lar</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="container-fluid">
    <div class="row">
        <!--SideBar desplegable-->
        <div class="col-sm-auto px-0" style="display:none" id="mySidebar">
            <ul class="nav flex-column">
                <li> <a onclick="_close()"> <i class="fa-solid fa-xmark"></i></a></li>
                <hr style="height:10px">
                <li> <a href="#" class="w3-bar-item w3-button">Link 1</a></li>
                <li> <a href="#" class="w3-bar-item w3-button">Link 2</a></li>
                <li><a href="#" class="w3-bar-item w3-button">Link 3</a></li>
            </ul>
        </div>

        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><?= lang("registre.table-dispositius") ?></h1>
                </div>
                <div>
                    <button  onclick="_open()" class="btn" id="btn-filter"><i class="fa-solid fa-filter"></i> <?= lang("registre.buttons.filter") ?></button>
                    <a href="<?= base_url("/registreTiquet?export=xls") ?>" id="btn-export" class="btn btn-info" title="<?= lang("registre.buttons.export_title") ?>"><i class="fa-solid fa-file-excel"></i> <?= lang("registre.buttons.export") ?></a>
                    <a href="<?= base_url("/formulariTiquet") ?>" class="btn" id="btn-create"><i class="fa-solid fa-circle-plus"></i> <?= lang("registre.buttons.create") ?></a>
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