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



<?php if ($tipus_inventari_desactivar !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.tipus_inventari_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tipus/inventari") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('tipus.tipus_inventari_model_text') ?><?php echo $tipus_inventari_desactivar['nom_tipus_inventari'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarTipusInventari/" . $tipus_inventari_desactivar['id_tipus_inventari']) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tipus/inventari") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($tipus_intervencio_desactivar !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.tipus_intervencio_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tipus/intervencio") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('tipus.tipus_intervencio_model_text') ?><?php echo $tipus_intervencio_desactivar['nom_tipus_intervencio'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarTipusIntervencio/" . $tipus_intervencio_desactivar['id_tipus_intervencio']) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tipus/intervencio") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($curs_desactivar !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.curs_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tipus/curs") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('tipus.curs_model_text') ?><?php echo $curs_desactivar['titol'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarCurs/" . $curs_desactivar['id_curs']) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tipus/curs") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($tipus_dispositiu_desactivar !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.tipus_dispositiu_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tipus/dispositiu") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('tipus.tipus_dispositiu_model_text') ?><?php echo $tipus_dispositiu_desactivar['id_tipus_dispositiu'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarTipusDispositiu/" . $tipus_dispositiu_desactivar['id_tipus_dispositiu']) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tipus/dispositiu") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($poblacio_desactivar !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.poblacio_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tipus/poblacio") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('tipus.poblacio_model_text') ?><?php echo $poblacio_desactivar['id_poblacio'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarPoblacio/" . $poblacio_desactivar['id_poblacio']) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tipus/poblacio") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($comarca_desactivar !== null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('tipus.comarca_model_title') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/tipus/comarca") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <p><?= lang('tipus.comarca_model_text') ?><?php echo $comarca_desactivar['id_comarca'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("/eliminarComarca/" . $comarca_desactivar['id_comarca']) ?>" type="button" class="btn btn-danger"><?= lang('registre.buttons.delete') ?></a>
                    <a href="<?= base_url("/tipus/comarca") ?>" type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('registre.buttons.cancel') ?></a>
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
                    <li class="nav-item" title="<?= lang("registre.professors") ?>">
                        <a href="<?= base_url("/professor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.professors") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-person-chalkboard"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($role == 'desenvolupador') : ?>
                    <li class="nav-item" id="actiu" title="<?= lang("registre.tipus") ?>">
                        <a href="<?= base_url("/tipus/dispositiu") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.tipus") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-gear"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>


            <div class="col-sm p-0 pb-3 ps-5 pe-5 min-vh-100" id="zona_taula">
                <div class="row mt-5 justify-content-center">
                    <div class="col-2 d-flex align-items-center">
                        <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets') ?>">
                            <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                        </a>
                    </div>

                    <div class="col-10 justify-content-left">
                        <nav class="navbar navbar-expand-lg navbar-light bg-light">
                            <div class="container-fluid">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="menu_tipus">
                                        <li class="nav-item">
                                            <?php if ($tipus_pantalla == "tipus_dispositiu"): ?>
                                                <a class="nav-link active actiu" aria-current="page" href="<?= base_url('/tipus/dispositiu') ?>"><?= lang('tipus.dispositiu_titol') ?></a>
                                            <?php else: ?>
                                                <a class="nav-link" aria-current="page" href="<?= base_url('/tipus/dispositiu') ?>"><?= lang('tipus.dispositiu_titol') ?></a>
                                            <?php endif; ?>
                                        </li>
                                        <li class="nav-item">
                                            <?php if ($tipus_pantalla == "tipus_inventari"): ?>
                                                <a class="nav-link active actiu" aria-current="page" href="<?= base_url('/tipus/inventari') ?>"><?= lang('tipus.tipus_inventari') ?></a>
                                            <?php else: ?>
                                                <a class="nav-link" aria-current="page" href="<?= base_url('/tipus/inventari') ?>"><?= lang('tipus.tipus_inventari') ?></a>
                                            <?php endif; ?>
                                        </li>
                                        <li class="nav-item">
                                            <?php if ($tipus_pantalla == "tipus_intervencio"): ?>
                                                <a class="nav-link active actiu" aria-current="page" href="<?= base_url('/tipus/intervencio') ?>"><?= lang('tipus.tipus_intervencio') ?></a>
                                            <?php else: ?>
                                                <a class="nav-link" aria-current="page" href="<?= base_url('/tipus/intervencio') ?>"><?= lang('tipus.tipus_intervencio') ?></a>
                                            <?php endif; ?>
                                        </li>
                                        <li class="nav-item">
                                            <?php if ($tipus_pantalla == "curs"): ?>
                                                <a class="nav-link active actiu" aria-current="page" href="<?= base_url('/tipus/curs') ?>"><?= lang('tipus.curs_titol') ?></a>
                                            <?php else: ?>
                                                <a class="nav-link" aria-current="page" href="<?= base_url('/tipus/curs') ?>"><?= lang('tipus.curs_titol') ?></a>
                                            <?php endif; ?>
                                        </li>
                                        <li class="nav-item">
                                            <?php if ($tipus_pantalla == "poblacio"): ?>
                                                <a class="nav-link active actiu" aria-current="page" href="<?= base_url('/tipus/poblacio') ?>"><?= lang('tipus.poblacio_titol') ?></a>
                                            <?php else: ?>
                                                <a class="nav-link" aria-current="page" href="<?= base_url('/tipus/poblacio') ?>"><?= lang('tipus.poblacio_titol') ?></a>
                                            <?php endif; ?>
                                        </li>
                                        <li class="nav-item">
                                            <?php if ($tipus_pantalla == "comarca"): ?>
                                                <a class="nav-link active actiu" aria-current="page" href="<?= base_url('/tipus/comarca') ?>"><?= lang('tipus.comarca_titol') ?></a>
                                            <?php else: ?>
                                                <a class="nav-link" aria-current="page" href="<?= base_url('/tipus/comarca') ?>"><?= lang('tipus.comarca_titol') ?></a>
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>

                </div>

            <?php if($tipus_pantalla == "tipus_inventari"): ?> 

                <div>
                    <?php if ((session()->get('tipus_inventari_buit')) !== null) : ?>
                        <div class="alert alert-warning alerta_esborrar" role="alert">
                            <?= session()->get('tipus_inventari_buit') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_inventari_existeix')) !== null) : ?>
                        <div class="alert alert-warning alerta_esborrar" role="alert">
                            <?= session()->get('tipus_inventari_existeix') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_inventari_desactivat')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('tipus_inventari_desactivat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_inventari_esborrat')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('tipus_inventari_esborrat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_inventari_creat')) !== null) : ?>
                        <div class="alert alert-success alerta_esborrar" role="alert">
                            <?= session()->get('tipus_inventari_creat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_inventari_activat')) !== null) : ?>
                        <div class="alert alert-success alerta_esborrar" role="alert">
                            <?= session()->get('tipus_inventari_activat') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row border mt-4 ms-1 me-0 pe-0 ps-0">
                    <div class="row form_header p-3 ms-0">
                        
                    </div>
                </div>

                <form method="POST" action="/tipus/inventari/afegir" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-9 mt-4">
                            <input type="text" class="form-control" name="tipus_inventari" id="tipusInventari" placeholder="<?= lang('tipus.escriu_tipus_inventari') ?>" required>
                        </div>
                        <div class="col-3 mt-4 d">
                            <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('tipus.afegir_tipus_inventari') ?></button>
                        </div>
                    </div>
                </form>


            <?php elseif($tipus_pantalla == "tipus_intervencio"): ?>

                    <div>
                        <?php if ((session()->get('tipus_intervencio_buit')) !== null) : ?>
                            <div class="alert alert-warning alerta_esborrar" role="alert">
                                <?= session()->get('tipus_intervencio_buit') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('tipus_intervencio_existeix')) !== null) : ?>
                            <div class="alert alert-warning alerta_esborrar" role="alert">
                                <?= session()->get('tipus_intervencio_existeix') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('tipus_intervencio_desactivat')) !== null) : ?>
                            <div class="alert alert-danger alerta_esborrar" role="alert">
                                <?= session()->get('tipus_intervencio_desactivat') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('tipus_intervencio_esborrat')) !== null) : ?>
                            <div class="alert alert-danger alerta_esborrar" role="alert">
                                <?= session()->get('tipus_intervencio_esborrat') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('tipus_intervencio_creat')) !== null) : ?>
                            <div class="alert alert-success alerta_esborrar" role="alert">
                                <?= session()->get('tipus_intervencio_creat') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('tipus_intervencio_activat')) !== null) : ?>
                            <div class="alert alert-success alerta_esborrar" role="alert">
                                <?= session()->get('tipus_intervencio_activat') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row border mt-4 ms-1 me-0 pe-0 ps-0">
                        <div class="row form_header p-3 ms-0">
                            
                        </div>
                    </div>

                    <form method="POST" action="/tipus/intervencio/afegir" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-9 mt-4">
                                <input type="text" class="form-control" name="tipus_intervencio" id="tipusIntervencio" placeholder="<?= lang('tipus.escriu_tipus_intervencio') ?>" required>
                            </div>
                            <div class="col-3 mt-4 d">
                                <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('tipus.afegir_tipus_intervencio') ?></button>
                            </div>
                        </div>
                    </form>

            <?php elseif($tipus_pantalla == "curs"): ?>

                    <div>
                        <?php if ((session()->get('curs_buit')) !== null) : ?>
                            <div class="alert alert-warning alerta_esborrar" role="alert">
                                <?= session()->get('curs_buit') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('curs_existeix')) !== null) : ?>
                            <div class="alert alert-warning alerta_esborrar" role="alert">
                                <?= session()->get('curs_existeix') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('curs_desactivat')) !== null) : ?>
                            <div class="alert alert-danger alerta_esborrar" role="alert">
                                <?= session()->get('curs_desactivat') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('curs_esborrat')) !== null) : ?>
                            <div class="alert alert-danger alerta_esborrar" role="alert">
                                <?= session()->get('curs_esborrat') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('curs_creat')) !== null) : ?>
                            <div class="alert alert-success alerta_esborrar" role="alert">
                                <?= session()->get('curs_creat') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ((session()->get('curs_activat')) !== null) : ?>
                            <div class="alert alert-success alerta_esborrar" role="alert">
                                <?= session()->get('curs_activat') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row border mt-4 ms-1 me-0 pe-0 ps-0">
                        <div class="row form_header p-3 ms-0">
                            
                        </div>
                    </div>

                    <form method="POST" action="/tipus/curs/afegir" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-2 mt-4">
                                <input type="number" min="0" class="form-control" name="curs" id="curs_crud" placeholder="<?= lang('tipus.escriu_curs') ?>" required>
                            </div>
                            <div class="col-3 mt-4">
                                <input type="text" class="form-control" name="cicle" id="cicle_crud" placeholder="<?= lang('tipus.escriu_cicle') ?>" required>
                            </div>
                            <div class="col-4 mt-4">
                                <input type="text" class="form-control" name="titol" id="titol_crud" placeholder="<?= lang('tipus.escriu_titol') ?>" required>
                            </div>
                            <div class="col-3 mt-4 d">
                                <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('tipus.afegir_curs') ?></button>
                            </div>
                        </div>
                    </form>

            <?php elseif($tipus_pantalla == "tipus_dispositiu"): ?>

                <div>
                    <?php if ((session()->get('tipus_dispositiu_buit')) !== null) : ?>
                        <div class="alert alert-warning alerta_esborrar" role="alert">
                            <?= session()->get('tipus_dispositiu_buit') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_dispositiu_existeix')) !== null) : ?>
                        <div class="alert alert-warning alerta_esborrar" role="alert">
                            <?= session()->get('tipus_dispositiu_existeix') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_dispositiu_desactivat')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('tipus_dispositiu_desactivat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_dispositiu_esborrat')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('tipus_dispositiu_esborrat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_dispositiu_creat')) !== null) : ?>
                        <div class="alert alert-success alerta_esborrar" role="alert">
                            <?= session()->get('tipus_dispositiu_creat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('tipus_dispositiu_activat')) !== null) : ?>
                        <div class="alert alert-success alerta_esborrar" role="alert">
                            <?= session()->get('tipus_dispositiu_activat') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row border mt-4 ms-1 me-0 pe-0 ps-0">
                    <div class="row form_header p-3 ms-0">
                        
                    </div>
                </div>

                <form method="POST" action="/tipus/dispositiu/afegir" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-9 mt-4">
                            <input type="text" class="form-control" name="tipus_dispositiu" id="tipus_dispositiu_crud" placeholder="<?= lang('tipus.escriu_tipus_dispositiu') ?>" required>
                        </div>
                        <div class="col-3 mt-4 d">
                            <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('tipus.afegir_tipus_dispositiu') ?></button>
                        </div>
                    </div>
                </form>

            <?php elseif($tipus_pantalla == "poblacio"): ?>

                <div>
                    <?php if ((session()->get('poblacio_buit')) !== null) : ?>
                        <div class="alert alert-warning alerta_esborrar" role="alert">
                            <?= session()->get('poblacio_buit') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('poblacio_existeix')) !== null) : ?>
                        <div class="alert alert-warning alerta_esborrar" role="alert">
                            <?= session()->get('poblacio_existeix') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('poblacio_desactivat')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('poblacio_desactivat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('poblacio_esborrat')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('poblacio_esborrat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('poblacio_creat')) !== null) : ?>
                        <div class="alert alert-success alerta_esborrar" role="alert">
                            <?= session()->get('poblacio_creat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('poblacio_activat')) !== null) : ?>
                        <div class="alert alert-success alerta_esborrar" role="alert">
                            <?= session()->get('poblacio_activat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('no_existeix_comarca')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('no_existeix_comarca') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('no_existeix_sstt')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('no_existeix_sstt') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row border mt-4 ms-1 me-0 pe-0 ps-0">
                    <div class="row form_header p-3 ms-0">
                        
                    </div>
                </div>

                <form method="POST" action="/tipus/poblacio/afegir" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-2 mt-4">
                            <input type="number" min="0" class="form-control" name="id_poblacio" id="id_poblacio_crud" placeholder="<?= lang('tipus.escriu_id_poblacio') ?>" title="<?= lang('tipus.escriu_id_poblacio') ?>" required>
                        </div>
                        <div class="col-2 mt-4">
                            <input type="number" min="0" class="form-control" name="codi_postal" id="codi_postal_crud" placeholder="<?= lang('tipus.escriu_codi_postal') ?>" title="<?= lang('tipus.escriu_codi_postal') ?>" required>
                        </div>
                        <div class="col-2 mt-4">
                            <input type="text" class="form-control" name="nom_poblacio" id="nom_poblacio_crud" placeholder="<?= lang('tipus.escriu_nom_poblacio') ?>" title="<?= lang('tipus.escriu_nom_poblacio') ?>" required>
                        </div>
                        <div class="col-2 mt-4">
                            <input id="id_comarca" list="dataListComarques" name="id_comarca" class="form-select selector_filtre" placeholder="<?= lang('tipus.escriu_id_comarca') ?>" title="<?= lang('tipus.escriu_id_comarca') ?>" required />
                            <datalist id="dataListComarques">
                                <?= $comarques ?>
                            </datalist>
                        </div>
                        <div class="col-2 mt-4">
                            <input id="id_sstt" list="dataListSSTT" name="id_sstt" class="form-select selector_filtre" placeholder="<?= lang('tipus.escriu_id_sstt') ?>" title="<?= lang('tipus.escriu_id_sstt') ?>" required />
                            <datalist id="dataListSSTT">
                                <?= $sstt ?>
                            </datalist>
                        </div>
                        <div class="col-2 mt-4 d">
                            <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('tipus.afegir_poblacio') ?></button>
                        </div>
                    </div>
                </form>

            <?php elseif($tipus_pantalla == "comarca"): ?>

                <div>
                    <?php if ((session()->get('comarca_buit')) !== null) : ?>
                        <div class="alert alert-warning alerta_esborrar" role="alert">
                            <?= session()->get('comarca_buit') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('comarca_existeix')) !== null) : ?>
                        <div class="alert alert-warning alerta_esborrar" role="alert">
                            <?= session()->get('comarca_existeix') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('comarca_desactivat')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('comarca_desactivat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('comarca_esborrat')) !== null) : ?>
                        <div class="alert alert-danger alerta_esborrar" role="alert">
                            <?= session()->get('comarca_esborrat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('comarca_creat')) !== null) : ?>
                        <div class="alert alert-success alerta_esborrar" role="alert">
                            <?= session()->get('comarca_creat') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((session()->get('comarca_activat')) !== null) : ?>
                        <div class="alert alert-success alerta_esborrar" role="alert">
                            <?= session()->get('comarca_activat') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row border mt-4 ms-1 me-0 pe-0 ps-0">
                    <div class="row form_header p-3 ms-0">
                        
                    </div>
                </div>

                <form method="POST" action="/tipus/comarca/afegir" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-3 mt-4">
                            <input type="number" min="0" class="form-control" name="id_comarca" id="id_comarca_crud" placeholder="<?= lang('tipus.escriu_id_comarca') ?>" required>
                        </div>
                        <div class="col-6 mt-4">
                            <input type="text" class="form-control" name="nom_comarca" id="nom_comarca_crud" placeholder="<?= lang('tipus.escriu_nom_comarca') ?>" required>
                        </div>
                        <div class="col-3 mt-4 d">
                            <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus"></i> <?= lang('tipus.afegir_comarca') ?></button>
                        </div>
                    </div>
                </form>

            <?php endif; ?>

            <div class="row">
                <div class="col">
                        <?= $output ?>
                </div>
            </div>

        </div>



    </div>

</div>
<script>
</script>
<?= $this->endSection('contingut'); ?>