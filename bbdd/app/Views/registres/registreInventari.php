<?= $this->extend('layouts/professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'style.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>

<div class="container-fluid p-0 overflow-hidden">
    <div class="row">
        <!--Sidebar estàtic-->
        <div class="col-sm-auto pl-0" id="sidebar">
            <?php if ($role == 'professor') : ?>
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
            <?php endif; ?>
            <?php if ($role == 'alumne') : ?>
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
            <?php endif; ?>
            <?php if ($role == 'sstt') : ?>
                <ul class="nav flex-column">
                    <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                        <a href="<?= base_url("/tiquets/emissor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                    </li>
                    <li class="nav-item" id="actiu" title="<?= lang("registre.inventari") ?>">
                        <a href="<?= base_url("/inventari") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
            <?php if ($role == 'admin_sstt' || $role == 'desenvolupador') : ?>
                <ul class="nav flex-column">
                    <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                        <a href="<?= base_url("/tiquets/emissor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                    </li>
                    <li class="nav-item" id="actiu" title="<?= lang("registre.inventari") ?>">
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
            <?php endif; ?>
        </div>

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
        <?php if ($no_permisos != null) : ?>
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

        <!--Taula i títol-->
        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <div class="d-flex justify-content-between align-items-center" id="contenidor_titol">
                <div>
                    <h1><?= lang("inventari.registre_inventari") ?></h1>
                </div>
                <?php if ($role !== 'alumne' && $role !== 'sstt') : ?>
                    <div id="botons_titol">
                        <a href="<?= base_url("/inventari/afegir") ?>" class="btn" id="btn-create"><i class="fa-solid fa-circle-plus"></i> <?= lang("inventari.button_afegir_inventari") ?></a>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <?= $output ?>
            </div>
        </div>
    </div>
</div>
<script>
(function(window, document, undefined) {
        window.onload = init;

        function init() {
            var buscador = document.getElementById("data-list-vista_inventari_filter");
            buscador.style = "display: none;";
            var nou_buscador = buscador;
            nou_buscador.style = "display: unset";
            nou_buscador.classList.add("d-flex");
            var main = document.getElementById("contenidor_titol");
            var botons = document.getElementById("botons_titol");
            if(botons !== null){
                main.removeChild(botons);
            }


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
            if(botons !== null){
                main.appendChild(botons);
            }

            var paginador = document.getElementById("data-list-vista_inventari_length");
            console.log(paginador);
            var pare_paginador = paginador.parentElement;
            pare_paginador.removeChild(paginador);

            var final_taula = document.getElementById("data-list-vista_inventari_info");
            var pare_final_taula = final_taula.parentElement;
            pare_final_taula.appendChild(paginador);
        }

    })(window, document, undefined);
</script>
<?= $this->endSection('contingut'); ?>