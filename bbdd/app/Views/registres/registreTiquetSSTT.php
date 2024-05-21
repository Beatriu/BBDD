<?= $this->extend('layouts' . DIRECTORY_SEPARATOR . 'professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'style.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts' . DIRECTORY_SEPARATOR . 'header.php'); ?>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>

<?php if ($id_tiquet !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= lang('registre.model_title') ?></h5>
                    <a href="<?= base_url("/tiquets") ?>">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <p><?= lang('registre.model_text') ?><?php echo session()->getFlashdata('tiquet')["codi_equip"]; ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarTiquet/" . $id_tiquet) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tiquets") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="container-fluid p-0 overflow-hidden">
    <div class="row">
        <!--Sidebar estàtic-->
        <div class="col-sm-auto pl-0" id="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item" id="actiu" title="<?= lang("registre.dispositius_rebuts") ?>">
                    <a href="<?= base_url("/tiquets/emissor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                        <i class="fa-solid fa-list-check"></i>
                    </a>
                </li>
                <li class="nav-item" title="<?= lang("registre.inventari") ?>">
                    <a href="<?= base_url("/inventari") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </a>
                </li>
                <?php if ($tipus_sstt == 'admin' || $tipus_sstt == 'desenvolupador') : ?>
                    <li class="nav-item" title="<?= lang("registre.alumnes") ?>">
                        <a href="<?= base_url("/alumnes") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.alumnes") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-users"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <!--SideBar desplegable-->
        <div class="col-sm-auto px-0" style="display:none" id="mySidebar">
            <div class="nav flex-column">
                <div class="d-flex justify-content-end">
                    <a onclick="_close()"> <i class="fa-solid fa-xmark"></i></a>
                </div>
                <h1 style="color:#FFFFFF;">CERCA</h1>
                <div class="linia"></div>
            </div>
        </div>

        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><?= lang("registre.titol_dispositius_sstt") ?></h1>
                </div>
                <div>
                    <!--<button onclick="_open()" class="btn" id="btn-filter"><i class="fa-solid fa-filter"></i> <? //= lang("registre.buttons.filter") 
                                                                                                                    ?></button>-->
                    <a href="<?= base_url("/tiquets?export=xls") ?>" id="btn-export" class="btn btn-info" title="<?= lang("registre.buttons.export_title") ?>"><i class="fa-solid fa-file-excel"></i> <?= lang("registre.buttons.export") ?></a>
                    <a href="<?= base_url("/formulariTiquet") ?>" class="btn" id="btn-create"><i class="fa-solid fa-circle-plus"></i> <?= lang("registre.buttons.create") ?></a>
                </div>
            </div>
            <div>
                <?php if ($error != null) {
                    echo lang($error);
                } ?>
                <?= $output ?>
            </div>
        </div>
    </div>
</div>
<script>
    var taula = document.getElementById('data-list-vista_tiquet');
    var childitems = taule.children.item(1);
    //console.log(hola.children.item(1));
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