<?= $this->extend('layouts/professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'style.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>

<?php if ($llista_admesos_desactivar !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.llista_admesos_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/professor") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('tipus.llista_admesos_model_text') ?> <?php echo $llista_admesos_desactivar['correu_professor'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarProfessor/" . $llista_admesos_desactivar['correu_professor']) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/professor") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($eliminar_tots !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.llista_admesos_tots_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/professor") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body text-danger">
                    <p><?= lang('tipus.llista_admesos_tots_model_text') ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarTotsProfessors") ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/professor") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<div class="container-fluid p-0 overflow-hidden">

    <div class="row">
        <!--Sidebar estàtic-->
        <div div class="col-sm-auto pl-0" id="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                    <a href="<?= base_url("/tiquets/emissor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                        <i class="fa-solid fa-list-check"></i>
                    </a>
                </li>
                <li class="nav-item" title="<?= lang("registre.inventari") ?>">
                    <a href="<?= base_url("/inventari") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </a>
                </li>
                <?php if ($role == 'admin_sstt' || $role == 'desenvolupador') : ?>
                    <li class="nav-item" title="<?= lang("registre.alumnes") ?>">
                        <a href="<?= base_url("/alumnes") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.alumnes") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-users"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<?= base_url("/centres") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.centres") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                        <i class="fa-solid fa-school"></i>
                    </a>
                </li>
                <?php if ($role == 'admin_sstt' || $role == 'desenvolupador') : ?>
                    <li class="nav-item" title="<?= lang("registre.professors") ?>">
                        <a href="<?= base_url("/professor") ?>" id="actiu" class="nav-link py-3 px-2" title="<?= lang("registre.professors") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-person-chalkboard"></i>
                        </a>
                    </li>
                    <li class="nav-item" title="<?= lang("registre.dades") ?>">
                        <a href="<?= base_url("/dades") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.dades") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-chart-simple"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($role == 'desenvolupador') : ?>
                    <li class="nav-item" title="<?= lang("registre.tipus") ?>">
                        <a href="<?= base_url("/tipus/dispositiu") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.tipus") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-gear"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>


        <div class="col-sm p-0 pb-3 ps-5 pe-5 min-vh-100" id="zona_taula">
            <div class="row mt-5 d-flex justify-content-around">
                <div class="col-2 d-flex align-items-center">
                    <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets') ?>">
                        <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                    </a>
                </div>

                <div class="col-5 justify-content-left" >
                    <h2><?= lang('tipus.professors') ?></h2>
                </div>

                <div id="lloc_del_sidebar" class="col-3" style="margin-top:10px;">

                </div>

            </div>


            <div>
                <?php if ((session()->get('centre_no_existeix')) !== null) : ?>
                    <div class="alert alert-warning alerta_esborrar" role="alert">
                        <?= session()->get('centre_no_existeix') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('llista_admesos_buit')) !== null) : ?>
                    <div class="alert alert-warning alerta_esborrar" role="alert">
                        <?= session()->get('llista_admesos_buit') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('llista_admesos_existeix')) !== null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= session()->get('llista_admesos_existeix') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('llista_admesos_esborrat')) !== null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= session()->get('llista_admesos_esborrat') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('llista_admesos_creat')) !== null) : ?>
                    <div class="alert alert-success alerta_esborrar" role="alert">
                        <?= session()->get('llista_admesos_creat') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row border mt-4 ms-1 me-0 pe-0 ps-0">
                <div class="row form_header p-3 ms-0">

                </div>
            </div>

            <form method="POST" action="/professor/afegir" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-3 mt-4">
                        <input type="email" class="form-control" name="correu_professor" id="tipusInventari" placeholder="<?= lang('tipus.escriu_correu_professor') ?>" required>
                    </div>
                    <div class="col-2 mt-4">
                        <input type="date" class="form-control" name="data_entrada" id="tipusInventari" required>
                    </div>
                    <div class="col-2 mt-4">
                        <input id="codi_centre" list="dataListCentres" name="codi_centre" class="form-select selector_filtre" placeholder="<?= lang('tipus.escriu_codi_centre') ?>" title="<?= lang('tipus.escriu_id_comarca') ?>" required />
                        <datalist id="dataListCentres">
                            <?= $centres ?>
                        </datalist>
                    </div>
                    <div class="col-3 mt-4">
                        <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('tipus.afegir_llista_admesos') ?></button>
                    </div>
                    <?php if($role == "desenvolupador"): ?>
                        <div class="col-2 mt-4">
                            <a href="<?= base_url('/professor/desactivar/tots') ?>" class="btn btn-danger rounded-pill text-white">
                                <i class="fa-solid fa-trash text-white"></i> <?= lang('tipus.esborrar_tots_professors') ?>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </form>


            <div class="row">
                <div class="col">
                    <?= $output ?>
                </div>
            </div>

        </div>



    </div>

</div>
<script>

(function(window, document, undefined) {
        window.onload = init;

        function init() {
            var buscador = document.getElementById("data-list-llista_admesos_filter");
            buscador.style = "display: none;";
            var nou_buscador = buscador;
            nou_buscador.style = "display: unset";
            nou_buscador.classList.add("d-flex");
            var main = document.getElementById("zona_taula");
            var botons = document.getElementById("lloc_del_sidebar");

            var sidebar_des = document.getElementById("titol");
            var label = nou_buscador.firstChild;
            var input = label.lastChild;
            input.id = "input_buscador";
            input.style = "width:250px;";
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
            botons.appendChild(nou_buscador);

        }

    })(window, document, undefined);

</script>
<?= $this->endSection('contingut'); ?>