<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR .'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR .'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR .'style.css') ?>">

<link rel="stylesheet" href="<?= base_url('fontawesome/css/fontawesome.css') ?>" />
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>

    <div class="container mt-5">
        
        <div class="row">

            <div class="col-3">
                <img src="<?= base_url('/img/ordinador_defecte.png') ?>" alt="" width="200px" class="mb-3">

                <div class="w-17 border-right-1">
                    <h2><?= lang('intervencio.dades_tiquet.dades_titol') ?></h2>
                    <ul class="w-17 border-danger ">
                        <li><?= lang('intervencio.dades_tiquet.dades_codi') ?> </li>
                        <li><?= lang('intervencio.dades_tiquet.dades_tipus') ?> </li>
                        <li><?= lang('intervencio.dades_tiquet.dades_estat') ?> </li>
                    </ul>
                </div>
            </div>

            <div class="col-9">

            <form method="POST" action="<?= base_url('/vistaTiquet/cercar') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
                <div class="row">
                    
                    <div class="col-7">
                        <input class="form-control selector" name = "tiquet_seleccionat" list="datalistOptionsTiquets" id="tiquetsDataList" placeholder="<?= lang('intervencio.tiquets_datalist') ?>">
                        <datalist id="datalistOptionsTiquets">
                            <?= $options_tiquets ?>
                        </datalist>
                    </div>
                    <div class="col-2">
                        <button id="submit_cercar_tiquet" type="submit" class="btn btn-primary rounded-pill ms-3 me-3"><i class="fa-solid fa-magnifying-glass me-2"></i><?= lang('general_lang.buttons.buscar_button') ?></button>
                    </div>
                    <div class="col-3 d-flex justify-content-end">
                        <a href="<?= base_url("/afegir/intervencio/" . $id_tiquet) ?>" type="button" class="btn btn-success rounded-pill"><i class="fa-solid fa-plus me-2"></i><?= lang('intervencio.button_afegir_intervencio') ?></a>
                    </div>

                </div> 
            </form>


                <div class="row">
                    <?= $output ?>
                </div>
                
            </div>

        </div>
    </div>


<?= $this->endSection('contingut'); ?>