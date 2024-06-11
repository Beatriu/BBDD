<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<script>
    var opcions_tipus_dispositius = JSON.parse('<?= $json_tipus_dispositius ?>');
    var clic_escriu_codi = '<?= lang('general_lang.clic_escriu_codi') ?>';
    var clic_escriu_problema = '<?= lang('general_lang.clic_escriu_problema') ?>';
</script>
<link rel="stylesheet" href="<?= base_url('fontawesome' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'fontawesome.css') ?>" />
<script src="<?= base_url('js' . DIRECTORY_SEPARATOR . 'main_formulari_tiquet.js') ?>"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

<form class="container" method="POST" action="<?= base_url('/tiquets/afegir') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <!-- Modal llegenda -->
    <div class="modal" tabindex="-1" role="dialog" id="modal_alerta" style="display:none">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('registre.llegenda_tipus_dispositiu') ?></h5>
                    </div>
                    <div>
                        <button type="button" onclick="tancarModal()" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th><?= lang('registre.id_tipus_dispositiu') ?></th>
                                <th><?= lang('registre.nom_tipus_dispositiu') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($array_tipus_dispositiu); $i++) { ?>
                                <?php if ($array_tipus_dispositiu[$i]['actiu'] == "1") : ?>
                                    <tr>
                                        <td><?= $array_tipus_dispositiu[$i]['id_tipus_dispositiu'] ?></td>
                                        <td><?= $array_tipus_dispositiu[$i]['nom_tipus_dispositiu'] ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 justify-content-center">
        <div class="col-2 d-flex align-items-center">
            <?php if ($role != "professor") : ?>
                <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets') ?>">
                <?php else : ?>
                    <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets/emissor') ?>">
                    <?php endif; ?>
                    <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                    </a>
        </div>
        <div class="col-4 justify-content-left">
            <h1><?= lang('general_lang.create_tiquet') ?></h1>
        </div>
        <div class="col-6 d-flex align-items-center justify-content-end">
            <div class="d-flex justify-content-center align-items-center mt-3">
                <span id="mostrar_csv" class="ms-2"></span>
            </div>
            <div class="d-flex align-items-center">
                <button id="submit_afegir_csv" type="submit" class="btn btn-success rounded-pill ms-3 me-3 d-none"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('general_lang.save') ?></button>
                <div id="div_csv" class="btn btn_csv rounded-pill" onclick="afegirFitxer();"> <i class="fa-solid fa-file-csv me-2"></i><?= lang('general_lang.importar_csv') ?></div>
                <div id="cancelar_importar_csv" class="btn btn_cancell rounded-pill" onclick="cancellFitxer();" style="display: none;"><i class="fa-solid fa-xmark me-2"></i><?= lang('general_lang.cancell') ?></div>
                <input type="file" id="csv_tiquet" name="csv_tiquet" class="btn btn_csv rounded-pill" hidden onchange="mostrarFitxers(this);"> </input>
                <a id="div_csv_descarregar" href="<?= base_url('/descarregar/exemple_afegir_tiquet') ?>" class="btn btn_csv rounded-pill ms-3"> <i class="fa-solid fa-file-csv me-2"></i><?= lang('general_lang.plantilla_csv') ?></a>
                <button type="button" class="btn btn_save rounded-pill ms-3 " style="width: 40px;" onclick="obrirModal()"><i class="fa-solid fa-info"></i></button>
            </div>
        </div>
    </div>
    <?php if ((session()->get('error_filtre')) !== null) : ?>
        <div class="alert alert-danger alerta_esborrar" role="alert">
            <?= session()->get('error_filtre') ?>
        </div>
    <?php endif; ?>
    <?php if ((session()->get('error_csv')) !== null) : ?>
        <div class="alert alert-danger alerta_esborrar" role="alert">
            <?= session()->get('error_csv') ?>
        </div>
    <?php endif; ?>
    <div class="row border mt-4 me-0 pe-0 ps-0">
        <div class="row form_header pt-2 pb-2 ps-3 pe-3 ms-0">
            <span><?= lang('general_lang.informacio_comuna') ?></span>
        </div>
    </div>
    

    <div class="row mt-3">
        <div class="col">
            <label class="form-label" for="sNomContacteCentre"><?= lang('general_lang.name') ?> *</label>
            <input class=" form-control sNomContacteCentre" type="text" id="sNomContacteCentre" name="sNomContacteCentre" placeholder="<?= lang('general_lang.name') ?>" value="<?= $nom_persona_contacte_centre ?>" required />
        </div>
        <div class="col">
            <label class="form-label" for="sCorreuContacteCentre"><?= lang('general_lang.contact') ?> *</label>
            <input class="form-control sCorreuContacteCentre" type="text" id="sCorreuContacteCentre" name="sCorreuContacteCentre" placeholder="<?= lang('general_lang.contact') ?>" value="<?= $correu_persona_contacte_centre ?>" required />
        </div>
    </div>

    <?php if (session()->get('user_data')['role'] == "desenvolupador" || session()->get('user_data')['role'] == "admin_sstt" || session()->get('user_data')['role'] == "sstt") : ?>
        <div class="row mt-3">
            <div class="col">
                <label class="form-label" for="institutsDataListEmissor"><?= lang('general_lang.centre_emissor_curt') ?></label>
                <input class="form-control selector" name="centre_emissor" list="datalistOptionsEmissor" id="institutsDataListEmissor" placeholder="<?= lang('general_lang.centre_emissor') ?>">
                <datalist id="datalistOptionsEmissor">
                    <?= $centres_emissors ?>
                </datalist>
            </div>
            <div class="col">
                <label class="form-label" for="institutsDataListReparador"><?= lang('general_lang.centre_reparador_curt') ?></label>
                <input class="form-control selector" name="centre_reparador" list="datalistOptionsReparador" id="institutsDataListReparador" placeholder="<?= lang('general_lang.centre_reparador') ?>">
                <datalist id="datalistOptionsReparador">
                    <?= $centres_reparadors ?>
                </datalist>
            </div>
            <?php if (session()->get('user_data')['role'] == "desenvolupador") : ?>
                <div class="col">
                    <label class="form-label" for="institutsDataListSSTT"><?= lang('general_lang.sstt') ?> *</label>
                    <input class="form-control selector" name="sstt" list="datalistOptionsSSTT" id="institutsDataListSSTT" placeholder="<?= lang('general_lang.sstt') ?>" required>
                    <datalist id="datalistOptionsSSTT">
                        <?= $sstt ?>
                    </datalist>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="row border mt-5 me-0 pe-0 ps-0">
        <div class="row form_header p-1 ms-0">
            <div class="col d-flex align-items-center justify-content-center">
                <?= lang('general_lang.equipment_code') ?> *
            </div>
            <div class="col d-flex align-items-center justify-content-center">
                <?= lang('general_lang.type') ?> *
            </div>
            <div class="col d-flex align-items-center justify-content-center">
                <?= lang('general_lang.problem') ?> *
            </div>
            <div class="col-1 d-flex align-items-center justify-content-center">

            </div>
        </div>
        <div class="row me-0 pe-0 ms-1" id="div_files_formulari_tiquet">

            <div class="row p-2" id="fila_formulari_tiquet_1">
                <div class="col d-flex align-items-center justify-content-center">
                    <input id="equipment_code_1" class="form-control" title="Codi equip línea 1" type="text" name="provisional_equipment_code_1" oninput="afegirTiquetDisabled('fila_formulari_tiquet_1');" placeholder="<?= lang('general_lang.clic_escriu_codi') ?> 1" required />
                    <button type="button" id="random_pass_button_1" onclick="generar_pass(1)" class="btn rounded-pill ms-1 me-3 random_pass_button" data-toggle="tooltip" data-bs-placement="right" title="<?= lang('general_lang.generate_equipment_code') ?>"><i class="fa-solid fa-shuffle"></i></button>
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <select id="type_1" class="form-select" name="provisional_type_1" title="Tipus dispositiu línea 1" onchange="afegirTiquetDisabled('fila_formulari_tiquet_1');" required>
                        <?= $tipus_dispositius ?>
                    </select>
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <textarea id="problem_1" class="form-control" title="Problema línea 1" type="text" name="provisional_problem_1" style="width: 100%; height: 30px;" oninput="afegirTiquetDisabled('fila_formulari_tiquet_1');" placeholder="<?= lang('general_lang.clic_escriu_problema') ?> 1" required></textarea>
                </div>
            </div>

        </div>

        <div class="row">
            <input type="hidden" name="num_tiquets" id="num_tiquets" value="0" />
        </div>

    </div>
    <div class="row justify-content-center mt-4">
        <div>
            <?= lang('general_lang.nombre_tiquets') ?>
            <span id="span_nombre_tiquets">0</span>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <button id="submit_afegir" type="submit" class="btn btn-success rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('general_lang.save') ?></button>
        </div>
    </div>

</form>
<script>
    function obrirModal() {
        var modal = document.getElementById("modal_alerta");
        modal.style = "display:block;";
    }

    function tancarModal() {
        var modal = document.getElementById("modal_alerta");
        modal.style = "display:none;";
    }

    function generar_pass(number) {
        let result = '';
        var caracters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890?!*';
        var longitud = 10;
        for (let index = 0; index < longitud; index++) {
            const random = Math.floor(Math.random() * caracters.length);
            result += caracters[random];
        }
        var input = document.getElementById("equipment_code_" + number);
        input.value = '';
        input.value = result;
    }
</script>
<?= $this->endSection('contingut'); ?>