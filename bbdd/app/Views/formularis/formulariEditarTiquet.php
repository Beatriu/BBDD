<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'fontawesome.css') ?>"/>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
    <?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

    <form class="container" method="POST" action="<?= base_url('/tiquets/editar') ?>">
        <?= csrf_field() ?>
        <div class="row mt-5 justify-content-center">
            <div class="col-2 d-flex align-items-center">

                <?php if($role == "professor" && $tiquet['codi_centre_emissor'] == session()->get('user_data')['codi_centre']): ?>
                    <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets/emissor') ?>">
                <?php else: ?>
                    <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets') ?>">
                <?php endif; ?>
                    <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                </a>
            </div>
            <div class="col-10 justify-content-left">
                <h1><?= lang('general_lang.edit_tiquet') ?></h1>
            </div>
        </div>

        <div class="row border mt-4 me-0 pe-0 ps-0">
            <div class="row form_header pt-2 pb-2 ps-3 pe-3 ms-0">
                <span><?= lang('general_lang.informacio_tiquet') ?></span>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <label class="form-label" for="sNomContacteCentre"><?= lang('general_lang.name') ?> *</label>
                <input class=" form-control sNomContacteCentre" type="text" id="sNomContacteCentre" name="sNomContacteCentre" placeholder="<?= lang('general_lang.name') ?>" value="<?= $tiquet['nom_persona_contacte_centre'] ?>" maxlength="64" <?= $informacio_required ?> <?= $informacio_editable ?>/>
            </div>
            <div class="col">
                <label class="form-label" for="sCorreuContacteCentre"><?= lang('general_lang.contact') ?> *</label>
                <input class="form-control sCorreuContacteCentre" type="text" id="sCorreuContacteCentre" name="sCorreuContacteCentre" placeholder="<?= lang('general_lang.contact') ?>" value="<?= $tiquet['correu_persona_contacte_centre'] ?>" maxlength="32" <?= $informacio_required ?> <?= $informacio_editable ?>/>
            </div>
        </div>

        <?php if($role == "desenvolupador" || $role == "admin_sstt" || $role == "sstt"): ?>
        <div class="row mt-3">
            <div class="col">
                <label class="form-label" for="institutsDataListEmissor"><?= lang('general_lang.centre_emissor_curt') ?></label>
                <input class="form-control selector" name = "centre_emissor" list="datalistOptionsEmissor" id="institutsDataListEmissor" placeholder="<?= lang('general_lang.centre_emissor') ?>" value="<?= $centre_emissor_selected ?>">
                <datalist id="datalistOptionsEmissor">
                    <?=$centres_emissors?>
                </datalist>
            </div>

            <div class="col">
                <label class="form-label" for="institutsDataListReparador"><?= lang('general_lang.centre_reparador_curt') ?></label>
                <?php if($role == "desenvolupador" || $role == "admin_sstt" || ($role == "sstt" && ($estat_tiquet == "Pendent de recollir" || $estat_tiquet == "Assignat i pendent de recollir" || $estat_tiquet == "Emmagatzemat a SSTT" || $estat_tiquet == "Assignat i emmagatzemat a SSTT"))): ?>
                    <input class="form-control selector" name = "centre_reparador" list="datalistOptionsReparador" id="institutsDataListReparador" placeholder="<?= lang('general_lang.centre_reparador') ?>" value="<?= $centre_reparador_selected ?>">
                <?php elseif($role == "sstt"): ?>
                    <input class="form-control selector" name = "centre_reparador" list="datalistOptionsReparador" id="institutsDataListReparador" placeholder="<?= lang('general_lang.centre_reparador') ?>" value="<?= $centre_reparador_selected ?>" disabled>
                <?php endif; ?>
                <datalist id="datalistOptionsReparador">
                    <?=$centres_reparadors?>
                </datalist>
            </div>
            
        </div>
        <?php endif; ?>

        <div class="row mt-3">
            <div class="col">
                <label class="form-label" for="equipment_code"><?= lang('registre.codi_equip') ?> *</label>
                <input id="equipment_code" type="text" name="equipment_code" class="form-control selector" placeholder="<?= lang('general_lang.centre_reparador') ?>" value="<?= $tiquet['codi_equip'] ?>" maxlength="32" <?= $informacio_required ?> <?= $informacio_editable ?>/>
            </div>
            <div class="col">
                <label class="form-label" for="type"><?= lang('registre.tipus_dispositiu') ?></label>
                <select id="type" name="type" class="form-select" value="<?= $tiquet['id_tipus_dispositiu'] ?>" <?= $informacio_editable ?>>
                    <?=$tipus_dispositius?>
                </select>
            </div>
            <div class="col">
                <label class="form-label" for="estat"><?= lang('registre.estat') ?></label>
                <select id="estat" name="estat" class="form-select" value="<?= $tiquet['id_estat'] ?>" <?= $estat_ediatble ?>>
                    <?=$estats?>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <label class="form-label" for="problem"><?= lang('registre.descripcio_avaria') ?> *</label>
                <textarea id="problem" type="text" name="problem" style="width: 100%;" maxlength="512" <?= $informacio_required ?> <?= $informacio_editable ?>><?= $tiquet['descripcio_avaria'] ?></textarea>
            </div>
        </div>

        <?= validation_list_errors() ?>

        <div class="row justify-content-center mt-4">
            <div class="d-flex justify-content-center">
                <button id="submit_afegir" type="submit" class="btn btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('registre.buttons.save_changes') ?></button>
            </div>
        </div>

    </form>

<?= $this->endSection('contingut'); ?>