<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css/formulari.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
    <script>
        var opcions_tipus_dispositius = JSON.parse('<?= $json_tipus_dispositius ?>');
    </script>
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/fontawesome.css') ?>"/>
    <script src="<?= base_url('js/main_formulari_tiquet.js') ?>"></script>
    <script src="https://kit.fontawesome.com/7f13a820d7.js" crossorigin="anonymous"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
    <?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>
    <form class="container" method="POST" action="<?= base_url($locale . '/formulariTiquet') ?>" enctype="multipart/form-data">
        <div class="row mt-5 justify-content-center">
            <h1><?= lang('general_lang.create_tiquet') ?></h1>
        </div>
        <div class="row mt-3">
            <div class="col">
                <label class="" for="sNomContacteCentre"><?= lang('general_lang.name') ?></label>
                <input class="sNomContacteCentre" type="text" id="sNomContacteCentre" name="sNomContacteCentre" value="<?= $nom_persona_contacte_centre ?>" required/>
            </div>
            <div class="col">
                <label class="" for="sCorreuContacteCentre"><?= lang('general_lang.contact') ?></label>
                <input class="sCorreuContacteCentre" type="text" id="sCorreuContacteCentre" name="sCorreuContacteCentre" value="<?= $correu_persona_contacte_centre ?>" required/>
            </div>
        </div>
        <div class="row border mt-4 me-0 pe-0 ps-0">
            <div class="row form_header p-2 ms-0">
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.equipment_code') ?>
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.type') ?>
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <?= lang('general_lang.problem') ?>
                </div>
                <div class="col d-flex align-items-center justify-content-end">
                    <button type="button" class="btn btn-success rounded-pill" onclick="afegirTiquet();"><i class="fa fa-plus"></i> Afegir</button>
                </div>
            </div>
            <div class="row me-0 pe-0 ms-1" id = "div_files_formulari_tiquet">

                <div class="row p-2" id = "fila_formulari_tiquet_1">
                    <div class="col d-flex align-items-center justify-content-center">
                        <input type="text" name="equipment_code_1" required/>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <select name="type_1">
                            <?=$tipus_dispositius?>
                        </select>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <input type="text" name="problem_1" required/>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <button type="button" class="btn btn-danger rounded-circle" onclick = "esborrarTiquet('fila_formulari_tiquet_1');">
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
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn_cancell rounded-pill"><i class="fa-solid fa-trash me-2"></i><?= lang('general_lang.cancell') ?></button>
                <button type="submit" class="btn btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('general_lang.save') ?></button>
                <input type="file" name="csv_tiquet[]" multiple class="btn btn_csv rounded-pill"><i class="fa-solid fa-file-csv me-2"></i>CSV</input>
            </div>
        </div>
    </form>

<?= $this->endSection('contingut'); ?>