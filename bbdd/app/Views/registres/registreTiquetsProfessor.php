<?= $this->extend('layouts/professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'style.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'sidebar.css') ?>">
<?php if($uri == "tiquets"): ?>
    <script>var role = '<?= $role ?>';</script>
<?php elseif($uri == "tiquets/emissor"): ?>
    <script>var role = 'centre_emissor';</script>
<?php endif; ?>
<script src="<?= base_url('js' . DIRECTORY_SEPARATOR . 'estats.js') ?>"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
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
                    <p><?= lang('registre.model_text') ?><?php echo session()->getFlashdata('tiquet')["id_tiquet"]; ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarTiquet/" . $id_tiquet) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tiquets/emissor") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
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
                <?php if ($uri == 'tiquets') : ?>
                    <li class="nav-item" id="actiu" title="<?= lang("registre.dispositius_rebuts") ?> ">
                    <?php else : ?>
                    <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?> ">
                    <?php endif; ?>
                    <a href="<?= base_url("/tiquets") ?>" class="nav-link py-3 px-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                        <i class="fa-solid fa-hammer"></i>
                    </a>
                    </li>
                    <?php if ($uri == 'tiquets/emissor') : ?>
                        <li class="nav-item" id="actiu" title="<?= lang("registre.dispositius_rebuts") ?>">
                        <?php else : ?>
                        <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                        <?php endif; ?>
                        <a href="<?= base_url("/tiquets/emissor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                        </li>

                        <li class="nav-item" title="<?= lang("registre.inventari") ?>">
                            <a href="<?= base_url("/inventari") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </a>
                        </li>

                        <li class="nav-item" title="<?= lang("registre.alumnes") ?>">
                            <a href="<?= base_url("/alumnes") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.alumnes") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                                <i class="fa-solid fa-users"></i>
                            </a>
                        </li>
            </ul>
        </div>
        <!--SideBar desplegable-->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar_desplegable" aria-labelledby="sidebar_desplegable">
            <div class="offcanvas-header">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <h1 style="color:#FFFFFF;">CERCA</h1>
                <div class="linia"></div>
            <ul class="nav flex-column">
               <li> <a href="#" class="w3-bar-item w3-button">Link 1</a></li>
               <li> <a href="#" class="w3-bar-item w3-button">Link 2</a></li>
               <li><a href="#" class="w3-bar-item w3-button">Link 3</a></li>
            </ul>
        </div>
        <!--Taula i títol-->
        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <div class="d-flex justify-content-between align-items-center" id="contenidor_titol">
                <div>
                    <?php if ($uri == 'tiquets/emissor') : ?>
                        <h1><?= lang("registre.table-dispositius") ?></h1>
                    <?php else : ?>
                        <h1><?= lang("registre.dispositius_rebuts") ?></h1>
                    <?php endif; ?>
                </div>
                <div id="botons_titol">
                    <button class="btn" id="btn-filter" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar_desplegable" aria-controls="sidebar_desplegable"><i class="fa-solid fa-filter"></i> <?= lang("registre.buttons.filter") ?></button>
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
    (function(window, document, undefined) {
        window.onload = init;

        function init() {
            var buscador = document.getElementById("data-list-vista_tiquet_filter");
            buscador.style = "display: none;";
            var nou_buscador = buscador;
            nou_buscador.style = "display: unset";
            nou_buscador.classList.add("d-flex");
            var main = document.getElementById("contenidor_titol");
            var botons = document.getElementById("botons_titol");
            main.removeChild(botons);


            var sidebar_des = document.getElementById("titol");
            var label = nou_buscador.firstChild;
            var input = label.lastChild;
            input.id = "input_buscador";
            input.classList.add("input_buscador_class");
            input.placeholder = "<?= lang("registre.searcher_placeholder") ?>";
            nou_buscador.textContent = '';

            var div = document.createElement('div');
            var _span = document.createElement('span');
            _span.id = "icono_busqueda";
            var icon = document.createElement('i');
            icon.classList.add("fa-solid");
            icon.classList.add("fa-magnifying-glass");

            _span.appendChild(icon);
            nou_buscador.appendChild(_span);
            nou_buscador.appendChild(input);
            div.appendChild(nou_buscador);
            main.appendChild(nou_buscador);
            main.appendChild(botons);

            var paginador = document.getElementById("data-list-vista_tiquet_length");
            var pare_paginador = paginador.parentElement;
            pare_paginador.removeChild(paginador);

            var final_taula = document.getElementById("data-list-vista_tiquet_info");
            var pare_final_taula = final_taula.parentElement;
            pare_final_taula.appendChild(paginador);
        }

    })(window, document, undefined);
</script>
<?= $this->endSection('contingut'); ?>