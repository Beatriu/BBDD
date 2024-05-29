<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
    <link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'fontawesome.css') ?>"/>
    <script src="<?= base_url('js' . DIRECTORY_SEPARATOR . 'main_formulari_tiquet.js') ?>"></script>
    <script src="<?= base_url('js' . DIRECTORY_SEPARATOR . 'formulari_afegir_intervencio.js') ?>"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
    <?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

    <form class="container" method="POST" action="<?= base_url('/afegir/intervencio') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row mt-5 justify-content-center">
            <div class="col-2 d-flex align-items-center">
                <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets/' . $id_tiquet) ?>">
                    <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                </a>
            </div>
            <div class="col-10 justify-content-left">
                <h1><?= lang('intervencio.formulari_intervencio') ?></h1>
            </div>
        </div>

        <div class="row border mt-4 me-0 pe-0 ps-0">
            <div class="row form_header pt-2 pb-2 ps-3 pe-3 ms-0">
                <span><?= lang('intervencio.tiquet_intervencio') ?><?= explode("-",$id_tiquet)[4] ?></span>
            </div>
        </div>

        <div class="row mt-3 mb-3">

            <div class="col-6">

                <div class="row">
                    <label for="tipus_intervencio" class="form-label mb-1"><?= lang('intervencio.tipus_intervencio') ?> *</label>
                    <input class="form-select selector" name = "tipus_intervencio" list="datalistOptionsTipusIntervencio" id="tipus_intervencio" placeholder="<?= lang('intervencio.tipus_intervencio') ?>" required>
                    <datalist id="datalistOptionsTipusIntervencio">
                        <?=$tipus_intervencio?>
                    </datalist>
                </div>
                <div class="row mt-2">
                    <label for="curs" class="form-label mb-1"><?= lang('intervencio.curs') ?> *</label>
                    <input class="form-select selector" name = "curs" list="datalistOptionsCursos" id="curs" placeholder="<?= lang('intervencio.curs') ?>" required>
                    <datalist id="datalistOptionsCursos">
                        <?=$cursos?>
                    </datalist>
                </div>
                <div class="row mt-2">
                    <label for="descripcio_intervencio" class="form-label mb-1"><?= lang('intervencio.descripcio_intervencio') ?></label>
                    <textarea maxlength="512" class="form-control" id="descripcio_intervencio" name="descripcio_intervencio" rows="3"></textarea>
                </div>

            </div>
            <div class="col-6">
                <div class="row">

                    <div class="d-flex align-items-center gap-1">
                        <div class="flex-grow-1">
                            <label for="intervencioDataListInventari" class="form-label mb-1"><?= lang('intervencio.button_assignar_inventari') ?></label>
                            <input class="form-select selector" name = "inventari" list="datalistOptionsInventari" id="intervencioDataListInventari" onchange="afegirInventari();" placeholder="<?= lang('intervencio.selecciona_inventari') ?>" >
                            <datalist id="datalistOptionsInventari">
                                <?=$inventari_list?>
                            </datalist>
                        </div>
                    </div>

                </div>
                <div class="row p-3">

                    <table class="table table-striped row-border dataTable no-footer" id="data-list-vista_inventari" style="width: 100%;" aria-describedby="data-list-vista_inventari_info">
                        <thead>
                            <tr>
                                <th class="sorting align-middle">Identificador peça</th>
                                <th class="sorting align-middle">Nom tipus peça</th>
                                <th class="sorting align-middle">Data compra peça</th>
                                <th class="sorting">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id = "tbody_inventari">
                            <tr id="item-2" class="odd">         
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

        <input type="hidden" id="inventari_json" name="inventari_json">


        <div class="row justify-content-center mt-4">
            <div class="d-flex justify-content-center align-items-center">
                <button id="submit_afegir" type="submit" class="btn btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('intervencio.save_intervencio') ?></button>
            </div>
        </div>

    </form>

<?= $this->endSection('contingut'); ?>