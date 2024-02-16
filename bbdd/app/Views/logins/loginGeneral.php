<?= $this->extend('layouts/general'); ?>

<?= $this->section('contingut'); ?>


<form class="d-flex align-items-center justify-content-center">
    <div class="w-25 p-3" id="formulari">
        <div class="form-group">
            <label class="d-flex justify-content-center" for="sUser" ><?= lang('crud.user') ?>:</label>
            <input class="input" type="text" id="sUser" placeholder="example@xtec.cat"/>
        </div>
        <br />
        <div class="form-group">
        <label class="d-flex justify-content-center" for="sPssw"><?= lang('crud.password') ?>:</label>
        <input class="input" type="text" id="sPssw"/>
        </div>
        <br />
        <div class="d-flex justify-content-center">
        <button class="btn btn-outline-dark"><?= lang('crud.buttons.enter') ?></button>
        </div>
    </div>
</form>
<?= $this->endSection('contingut'); ?>