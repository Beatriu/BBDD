<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'style.css') ?>">
<link rel="stylesheet" href="<?= base_url('fontawesome/css/fontawesome.css') ?>" />
<script src="<?= base_url('js' . DIRECTORY_SEPARATOR . 'registre_intervencio.js') ?>"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>
<?php if ($id_intervencio !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('intervencio.modal_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tiquets/" . $id_tiquet) ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>

                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('intervencio.modal_text') ?><?php echo $id_intervencio; ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarIntervencio/" . $id_tiquet . "/" . $id_intervencio) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tiquets/" . $id_tiquet) ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="container mt-5">

    <div class="row">

        <div class="col-3">
            <div class="row mb-5 ps-2">
                <a id="tornar_button_vista_tiquet" class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets') ?>">
                    <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                </a>
            </div>

            <img src="<?= base_url('/img/ordinador_defecte.png') ?>" alt="" width="200px" class="mb-3">

            <div class="row">
                <h2><?= lang('intervencio.dades_tiquet.dades_titol') ?></h2>
                <ul>
                    <li><?= lang('intervencio.dades_tiquet.dades_id_tiquet') ?>: <?= $tiquet['id_tiquet'] ?> </li>
                    <li><?= lang('intervencio.dades_tiquet.dades_codi') ?>: <?= $tiquet['codi_equip'] ?> </li>
                    <li><?= lang('intervencio.dades_tiquet.dades_tipus') ?>: <?= $tipus_dispositiu ?> </li>

                    <?php if ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador" || $role == "professor" || $role == "alumne") : ?>
                        <li><?= lang('registre.centre') ?>: <?= $nom_centre_emissor ?> </li>
                    <?php endif; ?>
                    <?php if ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador") : ?>
                        <li><?= lang('general_lang.name_curt') ?>: <?= $tiquet['nom_persona_contacte_centre'] ?> </li>
                        <li><?= lang('general_lang.contact_curt') ?>: <?= $tiquet['correu_persona_contacte_centre'] ?> </li>
                        <li><?= lang('registre.centre_reparador') ?>: <?= $nom_centre_reparador ?> </li>
                    <?php endif; ?>
                    <?php if ($role == "desenvolupador") : ?>
                        <li><?= lang('registre.id_sstt') ?> <?= $tiquet['id_sstt'] ?> </li>
                    <?php endif; ?>
                    <li><?= lang('registre.data_alta') ?>: <?= $tiquet['data_alta'] ?> </li>
                </ul>
                <textarea id="mostrar_descripcio_tiquet" rows="6" disabled><?= trim($tiquet['descripcio_avaria']) ?>
                    </textarea>
            </div>

        </div>

        <div class="col-9">

            <div class="row">

                <div class="col-5">
                    <form method="POST" action="<?= base_url('/tiquets/cercar') ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="d-flex align-items-center gap-1">
                            <div class="flex-grow-1">
                                <input class="form-control selector" name="tiquet_seleccionat" list="datalistOptionsTiquets" id="tiquetsDataList" placeholder="<?= lang('intervencio.tiquets_datalist') ?>">
                                <datalist id="datalistOptionsTiquets">
                                    <?= $options_tiquets ?>
                                </datalist>
                            </div>
                            <button id="submit_cercar_tiquet" type="submit" class="btn btn-primary rounded" title="<?= lang('general_lang.buttons.buscar_button') ?>">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>



                <?php if ($role == "alumne" && $estat_editable != "disabled") : ?>
                    <div class="col-1">

                    </div>
                    <div class="col-3">
                        <form method="POST" action="<?= base_url('/tiquets/editar') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="d-flex align-items-center gap-1">
                                <div class="flex-grow-1">
                                    <select id="estat" name="estat" class="form-select" <?= $estat_editable ?> title="Estat del tiquet.">
                                        <?= $estats ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-warning rounded"><i class="fa-solid fa-pen-to-square"></i></button>
                            </div>

                        </form>
                    </div>

                <?php else : ?>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-12">
                                <select id="estat" name="estat" class="form-select" <?= $estat_editable ?> title="Estat del tiquet.">
                                    <?= $estats ?>
                                </select>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>


                <div class="col-3 d-flex justify-content-end">
                    <?php if ($role != "sstt") : ?>
                        <a href="<?= base_url("/afegir/intervencio/" . $id_tiquet) ?>" type="button" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus me-2"></i><?= lang('intervencio.button_afegir_intervencio') ?></a>
                    <?php endif; ?>
                </div>

            </div>


            <div>
                <?php if ((session()->get('afegirIntervencio')) !== null) : ?>
                    <div class="alert alert-success alerta_esborrar" role="alert">
                        <?= session()->get('afegirIntervencio') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('eliminarIntervencio')) !== null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= session()->get('eliminarIntervencio') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('editarIntervencio')) !== null) : ?>
                    <div class="alert alert-warning alerta_esborrar" role="alert">
                        <?= session()->get('editarIntervencio') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row">
                <?= $output ?>
            </div>

            <?php if ($id_intervencio_vista != null) : ?>
                <textarea id="vista_descripcio_intervencio" class="mb-0 mt-3" rows="3" disabled><?= trim($descripcio_intervencio_vista) ?></textarea>
                <div id="output2" class="row pt-0 mt-0">
                    <?= $output2 ?>
                </div>
            <?php endif; ?>

        </div>

    </div>
</div>
<script>
    (function(window, document, undefined) {
        window.onload = init;

        function init() {
            var paginador = document.getElementById("data-list-vista_intervencio_length");
            var pare_paginador = paginador.parentElement;
            pare_paginador.removeChild(paginador);

            var final_taula = document.getElementById("data-list-vista_intervencio_info");
            var pare_final_taula = final_taula.parentElement;
            pare_final_taula.appendChild(paginador);
        }

    })(window, document, undefined);
</script>

<?= $this->endSection('contingut'); ?>