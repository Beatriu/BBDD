<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css/formulari.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
    <script>var opcions_tipus_dispositius = JSON.parse('<?= $json_tipus_dispositius ?>');</script>
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/fontawesome.css') ?>"/>
    <script src="<?= base_url('js/main_formulari_tiquet.js') ?>"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
    <?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

    <form class="container" method="POST" action="<?= base_url('/formulariTiquet') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row mt-5 justify-content-center">
            <div class="col-2 d-flex align-items-center">
                <?php if($role != "professor"): ?>
                    <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets') ?>">
                <?php else: ?>
                    <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets/emissor') ?>">
                <?php endif; ?>
                        <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                    </a>
            </div>
            <div class="col-10 justify-content-left">
                <h1><?= lang('general_lang.create_tiquet') ?></h1>
            </div>
        </div>

        <div class="row border mt-4 me-0 pe-0 ps-0">
            <div class="row form_header p-3 ms-0">
                <span><?= lang('general_lang.informacio_comuna') ?></span>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <label class="form-label" for="sNomContacteCentre"><?= lang('general_lang.name') ?> *</label>
                <input class=" form-control sNomContacteCentre" type="text" id="sNomContacteCentre" name="sNomContacteCentre" placeholder="<?= lang('general_lang.name') ?>" value="<?= $nom_persona_contacte_centre ?>" required/>
            </div>
            <div class="col">
                <label class="form-label" for="sCorreuContacteCentre"><?= lang('general_lang.contact') ?> *</label>
                <input class="form-control sCorreuContacteCentre" type="text" id="sCorreuContacteCentre" name="sCorreuContacteCentre" placeholder="<?= lang('general_lang.contact') ?>" value="<?= $correu_persona_contacte_centre ?>" required/>
            </div>
        </div>

        <?php if(session()->get('user_data')['role'] == "desenvolupador" || session()->get('user_data')['role'] == "admin_sstt" || session()->get('user_data')['role'] == "sstt"): ?>
        <div class="row mt-3">
            <div class="col">
                <label class="form-label" for="institutsDataListEmissor"><?= lang('general_lang.centre_emissor_curt') ?></label>
                <input class="form-control selector" name = "centre_emissor" list="datalistOptionsEmissor" id="institutsDataListEmissor" placeholder="<?= lang('general_lang.centre_emissor') ?>">
                <datalist id="datalistOptionsEmissor">
                    <?=$centres_emissors?>
                </datalist>
            </div>
            <div class="col">
                <label class="form-label" for="institutsDataListReparador"><?= lang('general_lang.centre_reparador_curt') ?></label>
                <input class="form-control selector" name = "centre_reparador" list="datalistOptionsReparador" id="institutsDataListReparador" placeholder="<?= lang('general_lang.centre_reparador') ?>">
                <datalist id="datalistOptionsReparador">
                    <?=$centres_reparadors?>
                </datalist>
            </div>
        </div>
        <?php endif; ?>

        <div class="row border mt-5 me-0 pe-0 ps-0">
            <div class="row form_header p-2 ms-0">
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.equipment_code') ?> *
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.type') ?> *
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.problem') ?> *
                </div>
                <div class="col d-flex align-items-center justify-content-end">
                    <button id="button_afegir_fila_tiquet" type="button" class="btn btn_afegir_linea rounded-pill text-white" onclick="afegirTiquet();"><i class="fa fa-plus"></i> <?= lang('general_lang.afegir_linea') ?></button>
                </div>
            </div>
            <div class="row me-0 pe-0 ms-1" id = "div_files_formulari_tiquet">

                <div class="row p-2" id = "fila_formulari_tiquet_1">
                    <div class="col d-flex align-items-center justify-content-center">
                        <input id="equipment_code_1" title="Codi equip línea 1" type="text" name="equipment_code_1" />
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <select id="type_1" name="type_1" title="Tipus dispositiu línea 1">
                            <?=$tipus_dispositius?>
                        </select>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <textarea id="problem_1" title="Problema línea 1" type="text" name="problem_1" style="width: 100%;"></textarea>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <button type="button" class="btn btn-danger rounded-circle" onclick = "esborrarTiquet('fila_formulari_tiquet_1');" title="Botó esborrar línea 1">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <input type="hidden" name="num_tiquets" id="num_tiquets" value="1" />
            </div>

        </div>
        <div class="row justify-content-center mt-4">
            <div>
                <?= lang('general_lang.nombre_tiquets') ?>
                <span id="span_nombre_tiquets">1</span>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <button id="submit_afegir" type="submit" class="btn btn-success rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('general_lang.save') ?></button>
                <div id="div_csv" class="btn btn_csv rounded-pill" onclick = "afegirFitxer();"> <i class="fa-solid fa-file-csv me-2"></i><?= lang('general_lang.importar_csv') ?></div>
                <div id="cancelar_importar_csv" class="btn btn_cancell rounded-pill" onclick="cancellFitxer();" style="display: none;"><i class="fa-solid fa-xmark me-2"></i><?= lang('general_lang.cancell') ?></div>
                <input type="file" id="csv_tiquet" name="csv_tiquet" class="btn btn_csv rounded-pill" hidden  onchange="mostrarFitxers(this);"> </input>
                <a id="div_csv_descarregar" href="<?= base_url('/descarregar/exemple_afegir_tiquet') ?>" class="btn btn_csv rounded-pill ms-3" > <i class="fa-solid fa-file-csv me-2"></i><?= lang('general_lang.plantilla_csv') ?></a>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <span id = "mostrar_csv" class="ms-2"></span>
            </div>
        </div>

    </form>

<?= $this->endSection('contingut'); ?>