<?= $this->extend('layouts/general'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/header.css') ?>">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<header class="d-flex justify-content-between" style="background-color: #333333;">
    <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />

    <a class="me-3" href="<?= base_url('/canviLanguage') ?>"><img class="ms-auto imatge" src="<?= base_url(lang('general_lang.banderilla')) ?>" /></a>

</header>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>

<form class="d-flex align-items-center justify-content-center" method="POST" action="<?= base_url('/login') ?>">
    <div class="w-25 p-3" id="formulari">
        <div class="form-group">
            <label class="d-flex justify-content-center" for="sUser"><?= lang('general_lang.user') ?>:</label>
            <input class="entrada" type="text" id="sUser" name="sUser" placeholder="example@xtec.cat" value="<?= old('sUser') ?>"/>
        </div>
        <br />
        <div class="form-group">
            <label class="d-flex justify-content-center" for="sPssw"><?= lang('general_lang.password') ?>:</label>
            <input class="entrada" type="password" id="sPssw" name="sPssw" />
        </div>
        <br/>
        <?= validation_list_errors() ?>
        <br />
        <div class="d-flex justify-content-center">
            <button class="btn btn-outline-dark"><?= lang('crud.buttons.enter') ?></button>
        </div>
        <br/>
        <div>
        <?php
            if (!isset($login_button)) {
                $user_data = session()->get('user_data');
                print_r($user_data);
            } else {
                echo '<div align="center">' . $login_button . '</div>';
            }
            ?>
        </div>
    </div>
</form>

<?= $this->endSection('contingut'); ?>