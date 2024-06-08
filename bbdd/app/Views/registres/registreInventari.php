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
                        <a href="<?= base_url('/tiquets') ?>" class="nav-link py-3 px-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-hammer"></i>
                        </a>
                    </li>

                    <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                        <a href="<?= base_url('/tiquets/emissor') ?>" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                    </li>

                    <li class="nav-item" id="actiu" title="<?= lang("registre.inventari") ?>">
                        <a href="<?= base_url('/inventari') ?>" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </a>
                    </li>

                    <li class="nav-item" title="<?= lang("registre.alumnes") ?>">
                        <a href="<?= base_url('/alumnes') ?>" class="nav-link py-3 px-2" title="<?= lang("registre.alumnes") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
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
                    <?php if ($role == 'desenvolupador') : ?>
                        <li class="nav-item" title="<?= lang("registre.tipus") ?>">
                            <a href="<?= base_url("/tipus/dispositiu") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.tipus") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                                <i class="fa-solid fa-gear"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
        <!--SideBar desplegable-->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar_desplegable" aria-labelledby="sidebar_desplegable">
            <div class="offcanvas-header">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div id="titol">
                <form method="POST" action="<?= base_url('/filtreInventari') ?>">
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
                    <?php if ($role == 'admin_sstt' || $role == 'desenvolupador' || $role == 'sstt') : ?>
                        <div class="nom_centre_reparador_div px-3">
                            <br>
                            <h5><?= lang('registre.title_div_nom_centre_reparador') ?></h5>
                            <?php if (isset($session_filtre['nom_centre_reparador'])) : ?>
                                <input list="nom_centre_reparador" name="nom_centre_reparador_list" class="form-select selector_filtre" value="<?= old('nom_centre_reparador_list') ?>" />
                            <?php else : ?>
                                <input list="nom_centre_reparador" name="nom_centre_reparador_list" class="form-select selector_filtre" />
                            <?php endif; ?>
                            <datalist id="nom_centre_reparador">
                                <?= $centre_reparador ?>
                            </datalist>
                        </div>
                    <?php endif; ?>
                    <div class="data_creacio_div px-3">
                        <br>
                        <h5><?= lang('registre.title_div_data_creacio') ?></h5>
                        <?php if (isset($session_filtre['data_creacio'])) : ?>
                            <input type="date" name="data_creacio" id="data_creacio" class="form-control" value="<?= old('data_creacio') ?>" />
                        <?php else : ?>
                            <input type="date" name="data_creacio" id="data_creacio" class="form-control" />
                        <?php endif; ?>
                    </div>
                    <?php if ($role == 'admin_sstt' || $role == 'desenvolupador' || $role == 'sstt') : ?>
                        <div class="poblacio_div px-3">
                            <br>
                            <h5><?= lang('registre.title_div_poblacio') ?></h5>
                            <?php if (isset($session_filtre['nom_poblacio'])) : ?>
                                <input list="nom_poblacio" name="nom_poblacio_list" class="form-select selector_filtre" value="<?= old('nom_poblacio_list') ?>" />
                            <?php else : ?>
                                <input list="nom_poblacio" name="nom_poblacio_list" class="form-select selector_filtre" />
                            <?php endif; ?>
                            <datalist id="nom_poblacio">
                                <?= $poblacio ?>
                            </datalist>
                        </div>
                        <div class="comarca_div px-3">
                            <br>
                            <h5><?= lang('registre.title_div_comarca') ?></h5>
                            <?php if (isset($session_filtre['nom_comarca'])) : ?>
                                <input list="nom_comarca" name="nom_comarca_list" class="form-select selector_filtre" value="<?= old('nom_comarca_list') ?>" />
                            <?php else : ?>
                                <input list="nom_comarca" name="nom_comarca_list" class="form-select selector_filtre" />
                            <?php endif; ?>
                            <datalist id="nom_comarca">
                                <?= $comarca ?>
                            </datalist>
                        </div>
                    <?php endif; ?>
                    <div class="botons_filtre d-flex">
                        <button id="submit_eliminar_filtres" name="submit_eliminar_filtres" type="submit" class="btn btn-danger btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-trash me-2" id="trash_icon"></i><?= lang('registre.delete_filters') ?></button>
                        <button id="submit_afegir_filtres" name="submit_afegir_filtres" type="submit" class="btn btn-primary btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('registre.save_filters') ?></button>
                    </div>
                </form>
            </div>
            <br>
        </div>

        <?php if ($id_inventari !== null) : ?>
            <div class="modal" tabindex="-1" role="dialog" style="display:block">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-between">
                            <div>
                                <h5 class="modal-title"><?= lang('inventari.eliminar_title') ?></h5>
                            </div>
                            <div>
                                <a href="<?= base_url("/inventari") ?>">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p><?= lang('inventari.eliminar_text') ?><?php echo $id_inventari; ?></p>
                        </div>
                        <div class="modal-footer">
                            <a href="<?= base_url("/inventari") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('inventari.cancel_eliminar') ?></a>
                            <a href="<?= base_url("/eliminarInventari/" . $id_inventari) ?>" type="button" class="btn btn-danger"><?= lang('inventari.eliminar') ?></a>
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
                <div id="botons_titol">
                    <button class="btn" id="btn-filter" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar_desplegable" aria-controls="sidebar_desplegable"><i class="fa-solid fa-filter"></i> <?= lang("registre.buttons.filter") ?></button></button>
                    <?php if ($role !== 'alumne' && $role !== 'sstt') : ?>
                        <a href="<?= base_url("/inventari/afegir") ?>" class="btn" id="btn-create"><i class="fa-solid fa-circle-plus"></i> <?= lang("inventari.button_afegir_inventari") ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <!--Filtres activats-->
            <?php if (isset($session_filtre)) : ?>
                <div class="d-flex div_filtres">
                    <form method="POST" action="<?= base_url('/eliminarFiltreInventari') ?>">
                        <?= csrf_field() ?>
                        <div class="row row-cols-auto d-flex align-items-center">
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
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_dispositiu') ?> <i class="fa-solid fa-arrow-right"></i> <?= $tipus_dispositiu_escollit ?><button type="button" onclick="enviar('Dispositiu')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($session_filtre['nom_centre_reparador'])) : ?>
                                <div class="col px-0 form-check form-check-inline">
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_centre_reparador') ?> <i class="fa-solid fa-arrow-right"></i> <?= $centre_reparador_escollit['nom_centre'] ?> <button type="button" onclick="enviar('Centre_reparador')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($session_filtre['data_creacio'])) : ?>
                                <div class="col px-0 form-check form-check-inline">
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_data') ?> <i class="fa-solid fa-arrow-right"></i> <?= $session_filtre['data_creacio'][0] ?> <button type="button" onclick="enviar('data_creacio')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($session_filtre['nom_poblacio'])) : ?>
                                <div class="col px-0 form-check form-check-inline">
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_poblacio') ?> <i class="fa-solid fa-arrow-right"></i> <?= $poblacio_escollida ?> <button type="button" onclick="enviar('Poblacio')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($session_filtre['nom_comarca'])) : ?>
                                <div class="col px-0 form-check form-check-inline">
                                    <span class="badge bg-light text-dark etiqueta"><?= lang('registre.title_filtre_checkbox_comarca') ?> <i class="fa-solid fa-arrow-right"></i> <?= $comarca_escollida ?> <button type="button" onclick="enviar('Comarca')" class="btn-close btn_etiqueta" aria-label="Close"></button></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div>
                <?php if ((session()->get('afegirInventari')) !== null) : ?>
                    <div class="alert alert-success alerta_esborrar" role="alert">
                        <?= session()->get('afegirInventari') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('esborrarInventari')) !== null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= session()->get('esborrarInventari') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('error_filtre')) !== null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= session()->get('error_filtre') ?>
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
            if (botons !== null) {
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
            if (botons !== null) {
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
<script>
    function enviar(x) {
        document.getElementById("operacio").value = x;
        document.forms[1].submit();
    }
</script>
<?= $this->endSection('contingut'); ?>