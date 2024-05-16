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

<?php if ($id_inventari !== null) : ?>
        <div class="modal" tabindex="-1" role="dialog" style="display:block">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= lang('inventari.eliminar_title') ?></h5>
                        <a href="<?= base_url("/inventari") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                    <div class="modal-body">
                        <p><?= lang('alumne.eliminar_text') ?><?php echo $id_inventari; ?></p>
                    </div>
                    <div class="modal-footer">
                        <a href="<?= base_url("/eliminarInventari/" . $id_inventari) ?>" type="button" class="btn btn-danger"><?= lang('inventari.eliminar') ?></a>
                        <a href="<?= base_url("/inventari") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('inventari.cancel_eliminar') ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($no_permisos != null): ?> 
        <div class="modal" tabindex="-1" role="dialog" style="display:block">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger"><?= lang('inventari.no_permisos_title') ?></h5>
                        <a href="<?= base_url("/inventari") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                    <div class="modal-body text-danger">
                        <p><?= lang($no_permisos) ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <!--Sidebar estàtic-->
        <div class="col-sm-auto px-0" id="sidebar">
        <?php if($role == 'professor'):?>
        <ul class="nav flex-column">
                    <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?> ">
                        <a href="/tiquets" class="nav-link py-3 px-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-hammer"></i>
                        </a>
                    </li>

                    <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                        <a href="/tiquets/emissor" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                    </li>

                    <li class="nav-item" id="actiu" title="<?= lang("registre.inventari") ?>">
                        <a href="/inventari" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </a>
                    </li>

                    <li class="nav-item" title="<?= lang("registre.alumnes") ?>">
                        <a href="/alumnes" class="nav-link py-3 px-2" title="<?= lang("registre.alumnes") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-users"></i>
                        </a>
                    </li>
            </ul>
            <?php endif;?>
            <?php if($role == 'alumne'):?>
                <ul class="nav flex-column">
                <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?> ">
                    <a href="/tiquets" class="nav-link py-3 px-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                        <i class="fa-solid fa-hammer"></i>
                    </a>
                </li>
                <li class="nav-item" id="actiu" title="<?= lang("registre.inventari") ?>">
                    <a href="<?= base_url("/inventari") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </a>
                </li>
            </ul>
            <?php endif;?>
            <?php if($role == 'sstt'):?>
            <ul class="nav flex-column">
                <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                    <a href="<?= base_url("/tiquets/emissor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                        <i class="fa-solid fa-list-check"></i>
                    </a>
                </li>
                <li class="nav-item"  id="actiu" title="<?= lang("registre.inventari") ?>">
                    <a href="<?= base_url("/inventari") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </a>
                </li>
            </ul>
            <?php endif;?>
        </div>

        <!--Taula i títol-->
        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><?= lang("inventari.registre_inventari") ?></h1>
                </div>
                <?php if($role !== 'alumne' && $role !== 'sstt'):?>
                <div>
                    <a href="<?= base_url("/inventari/afegir") ?>" class="btn" id="btn-create"><i class="fa-solid fa-circle-plus"></i> <?= lang("inventari.button_afegir_inventari") ?></a>
                </div>
                <?php endif;?>
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