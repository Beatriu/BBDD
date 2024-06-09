<?= $this->extend('layouts/professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'style.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'sidebar.css') ?>">
<?php if ($uri == "tiquets") : ?>
    <script>
        var role = '<?= $role ?>';
    </script>
<?php elseif ($uri == "tiquets/emissor") : ?>
    <script>
        var role = 'centre_emissor';
    </script>
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
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('registre.model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tiquets") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
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
            <div id="titol">
                <form method="POST" action="<?= base_url('/filtre') ?>">
                    <?= csrf_field() ?>
                    <h1><?= lang("registre.sidebar_search_title") ?></h1>
                    <div class="linia"></div>
                    <div class="tipus_dispositiu_div px-3">
                        <br>
                        <h5><?= lang('registre.title_div_tipus_dispositiu') ?></h5>
                        <select class="form-select selector_filtre" id="selector_tipus_dispositiu" name="selector_tipus_dispositiu" title="Tipus dispositiu línea 1">
                            <?= $tipus_dispositius ?>
                        </select>
                    </div>
                    <?php if ($repoemi !== 'emissor') : ?>
                        <div class="nom_centre_emissor_div px-3">
                            <br>
                            <h5><?= lang('registre.title_div_nom_centre_emissor') ?></h5>
                            <?php if (isset($session_filtre['nom_centre_emissor'])) : ?>
                                <input list="nom_centre_emissor" name="nom_centre_emissor_list" class="form-select selector_filtre" value="<?= old('nom_centre_emissor_list') ?>" />
                            <?php else : ?>
                                <input list="nom_centre_emissor" name="nom_centre_emissor_list" class="form-select selector_filtre" />
                            <?php endif; ?>
                            <datalist id="nom_centre_emissor">
                                <?= $centre_emissor ?>
                            </datalist>
                        </div>
                    <?php endif; ?>
                    <div class="estat_div px-3">
                        <br>
                        <h5><?= lang('registre.title_div_estat') ?></h5>
                        <select class="form-select selector_filtre" id="selector_tipus_estat" name="selector_tipus_estat">
                            <?= $estats ?>
                        </select>
                    </div>
                    <div class="data_creacio_div px-3">
                        <br>
                        <h5><?= lang('registre.title_div_data_inici') ?></h5>
                        <?php if (isset($session_filtre['data_creacio_inici'])) : ?>
                            <input type="date" onchange="afegirValor()" name="data_creacio_inici" id="data_creacio_inici" class="form-control" value="<?= old('data_creacio_inici') ?>" />
                        <?php else : ?>
                            <input type="date" onchange="afegirValor()" name="data_creacio_inici" id="data_creacio_inici" class="form-control" />
                        <?php endif; ?>
                    </div>
                    <div class="data_creacio_fi_div px-3">
                        <br>
                        <h5><?= lang('registre.title_div_data_fi') ?></h5>
                        <?php if (isset($session_filtre['data_creacio_fi'])) : ?>
                            <input type="date" name="data_creacio_fi" id="data_creacio_fi" class="form-control" value="<?= old('data_creacio_fi') ?>" />
                        <?php else : ?>
                            <input type="date" name="data_creacio_fi" id="data_creacio_fi" class="form-control" />
                        <?php endif; ?>
                    </div>
                    <div class="botons_filtre d-flex">
                        <button id="submit_eliminar_filtres" name="submit_eliminar_filtres" type="submit" class="btn btn-danger btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-trash me-2" id="trash_icon"></i><?= lang('registre.delete_filters') ?></button>
                        <button id="submit_afegir_filtres" name="submit_afegir_filtres" type="submit" class="btn btn-primary btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('registre.save_filters') ?></button>
                    </div>
                </form>
            </div>
            <br>
        </div>
        <!--Taula i títol-->
        <div class="col-sm p-3 min-vh-100" id="zona_taula">
            <!--Títol-->
            <div class="d-flex justify-content-between align-items-center" id="contenidor_titol">
                <div>
                    <?php if ($uri == 'tiquets/emissor') : ?>
                        <h1><?= lang("registre.table-dispositius") ?></h1>
                    <?php else : ?>
                        <h1><?= lang("registre.dispositius_rebuts") ?></h1>
                    <?php endif; ?>
                </div>
                <div id="botons_titol">
                    <?php if($uri !== 'tiquets/emissor'): ?>
                    <button class="btn" id="btn-filter" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar_desplegable" aria-controls="sidebar_desplegable"><i class="fa-solid fa-filter"></i> <?= lang("registre.buttons.filter") ?></button>
                    <?php endif;?>
                    <a href="<?= base_url("/formulariTiquet") ?>" class="btn" id="btn-create"><i class="fa-solid fa-circle-plus"></i> <?= lang("registre.buttons.create") ?></a>
                </div>
            </div>
            <!--Filtres-->
            <?php if (isset($session_filtre)) : ?>
                <div class="d-flex div_filtres">
                    <form method="POST" action="<?= base_url('/eliminarFiltre') ?>">
                        <?= csrf_field() ?>
                        <div class="row row-cols-auto d-flex align-items-center ">
                            <input type="hidden" name="operacio" id="operacio" value="" />
                            <?php if (count($session_filtre) !== 0) : ?>
                                <div class="col">
                                    <p style="margin-bottom: 0;"><?= lang('registre.title_activated_filters') ?></p>
                                </div>
                                <div class="col px-0 form-check form-check-inline">
                                    <button id="submit_eliminar_tots_filtres" name="submit_eliminar_filtres" type="submit" class="badge bg-danger text-white etiqueta"><i class="fa-solid fa-trash me-2" id="trash_icon"></i><?= lang('registre.delete_all_filters') ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row d-flex align-items-center">
                            <?php if (isset($session_filtre['tipus_dispositiu'])) : ?>
                                <div class="col px-0 form-check form-check-inline">
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_dispositiu') ?> <i class="fa-solid fa-arrow-right"></i> <?= $session_filtre['tipus_dispositiu'][0] ?><button type="button" onclick="enviar('Dispositiu')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($session_filtre['estat'])) : ?>
                                <div class="col px-0 form-check form-check-inline">
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_estat') ?> <i class="fa-solid fa-arrow-right"></i> <?= $estat_escollit ?> <button type="button" onclick="enviar('Estat')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($session_filtre['nom_centre_emissor'])) : ?>
                                <div class="col px-0 form-check form-check-inline">
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_centre_emissor') ?> <i class="fa-solid fa-arrow-right"></i> <?= $centre_emissor_escollit['nom_centre'] ?><button type="button" onclick="enviar('Centre_emissor')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($session_filtre['data_creacio_inici']) && isset($session_filtre['data_creacio_fi'])) : ?>
                                <div class="col px-0 form-check form-check-inline">
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_data') ?> <i class="fa-solid fa-arrow-right"></i> <?= $session_filtre['data_creacio_inici'][0] ?> , <?= $session_filtre['data_creacio_fi'][0] ?> <button type="button" onclick="enviar('data_creacio_inici')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <!--Taula-->
            <div>
                <?php if ($error != null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= lang($error) ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('crearTiquet')) !== null) : ?>
                    <div class="alert alert-success alerta_esborrar" role="alert">
                        <?= session()->get('crearTiquet') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('error_filtre')) !== null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= session()->get('error_filtre') ?>
                    </div>
                <?php endif; ?>
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

            let input_buscador = document.getElementById("input_buscador");
            input_buscador.addEventListener("click", () => this.actualitzarColorsEstats());
        }

    })(window, document, undefined);

    function afegirValor(){
        //Inputs date
        var data_inici = document.getElementById("data_creacio_inici");
        var data_fi = document.getElementById("data_creacio_fi");
        data_fi.value = data_inici.value;
    }
</script>
<script>
    function enviar(x) {
        document.getElementById("operacio").value = x;
        document.forms[1].submit();
    }
</script>
<script src=""></script>
<?= $this->endSection('contingut'); ?>