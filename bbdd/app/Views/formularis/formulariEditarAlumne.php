<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('fontawesome' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'fontawesome.css') ?>" />
<script src="<?= base_url('js' . DIRECTORY_SEPARATOR . 'main_formulari_tiquet.js') ?>"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>


<?= $this->section('contingut'); ?>
<?php if ($editar_alumne_error != null) : ?>
    <div class="modal" tabindex="-1" role="dialog" style="display:block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title"><?= lang('alumne.alerta') ?></h5>
                    </div>
                    <div>
                        <a href="<?= base_url("/alumnes/editar/$correu_editar") ?>">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body text-warning">
                    <p><?= lang($editar_alumne_error) ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<form class="container" method="POST" action="<?= base_url('/alumnes/editar') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row mt-5 justify-content-center">
        <div class="col-2 d-flex align-items-center">
            <a class="btn btn-dark rounded-pill" href="<?= base_url('/alumnes') ?>">
                <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
            </a>
        </div>
        <div class="col-10 justify-content-left">
            <h1><?= lang('alumne.formulari_alumne') ?></h1>
        </div>
    </div>
    <div>
        <p><?= lang('alertes.nota_formulari_editar_alumne')?></p>
    </div>
    <div class="row border mt-4 me-0 pe-0 ps-0">
        <div class="row form_header pt-2 pb-2 ps-3 pe-3 ms-0">
        </div>
    </div>
    <!--Zona inputs-->
    <div class="row mt-3 mb-3">
        <!--Nom-->
        <div class="col">
            <label for="nom_alumne" class="form-label"><?= lang('alumne.nom_alumne') ?> *</label>
            <input type="text" class="form-control" name="nom_alumne" id="nom_alumne" placeholder="<?= lang('alumne.nom_alumne') ?>" value="<?= $nom ?>" required>
        </div>
        <!-- Cognoms -->
        <div class="col">
            <label for="congoms_alumne" class="form-label"><?= lang('alumne.congoms_alumne') ?> *</label>
            <input type="text" class="form-control" name="congoms_alumne" id="congoms_alumne" placeholder="<?= lang('alumne.congoms_alumne') ?>" value="<?= $cognoms ?>" required>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <!--Correu-->
        <div class="col">
            <label for="correu_alumne" class="form-label"><?= lang('alumne.correu_alumne') ?> *</label>
            <input type="text" pattern="^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$" class="form-control" name="correu_alumne" id="correu_alumne" placeholder="<?= lang('alumne.correu_alumne') ?>" value="<?= $correu_alumne ?>" required>
        </div>
        <!--Constrasenya-->
        <div class="col">
            <label for="contrasenya_alumne" class="form-label"><?= lang('alumne.contrasenya') ?> *</label>
            <div class="row mb-3 px-2">
                <input type="password" class="form-control" name="contrasenya_alumne" id="contrasenya_alumne" placeholder="<?= lang('alumne.contrasenya') ?>" required>
                <button type="button" id="pass_button" onclick="veure_pass()"><i class="fa-solid fa-eye"></i></button>
                <button type="button" id="random_pass_button" onclick="generar_pass()" class="btn rounded-pill ms-3 me-3" data-toggle="tooltip" data-bs-placement="right" title="<?= lang('alumne.generate_pass') ?>"><i class="fa-solid fa-shuffle"></i></button>
            </div>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <?php if ($role == "admin_sstt" || $role == "desenvolupador") : ?>
            <div class="col">
                <label for="centre" class="form-label"><?= lang('alumne.centre') ?> *</label>
                <input class="form-control selector" name="centre" list="datalistOptionsCentres" id="centre" placeholder="<?= lang('alumne.centre') ?>" value="<?= $codi_centre ?>" required>
                <datalist id="datalistOptionsCentres">
                    <?= $centres ?>
                </datalist>
            </div>
        <?php endif; ?>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="d-flex justify-content-center align-items-center">
            <button id="submit_afegir" type="submit" class="btn btn_save rounded-pill ms-3 me-3"><i class="fa-solid fa-floppy-disk me-2"></i><?= lang('alumne.editar_alumne') ?></button>
        </div>
    </div>

</form>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function veure_pass() {
        var input = document.getElementById("contrasenya_alumne");
        input.type = "text";
        var boto = document.getElementById("pass_button");
        boto.innerText = '';
        boto.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
        boto.onclick = function() {
            censurar_pass()
        };
    }

    function censurar_pass() {
        var input = document.getElementById("contrasenya_alumne");
        input.type = "password";
        var boto = document.getElementById("pass_button");
        boto.innerText = '';
        boto.innerHTML = '<i class="fa-solid fa-eye"></i>';
        boto.onclick = function() {
            veure_pass()
        };
    }

    function generar_pass() {
        let result = '';
        var caracters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890?!*';
        var longitud = 10;
        for (let index = 0; index < longitud; index++) {
            const random = Math.floor(Math.random() * caracters.length);
            result += caracters[random];
        }
        var input = document.getElementById("contrasenya_alumne");
        input.value = '';
        input.value = result;
    }
</script>
<?= $this->endSection('contingut'); ?>