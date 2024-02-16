<?= $this->extend('layouts/general'); ?>

<?= $this->section('contingut'); ?>


<form class="d-flex align-items-center justify-content-center">
    <div class="w-25 p-3" id="formulari">
        <div class="form-group">
            <h1>Institut</h1>
            <select name="" id="">
                <option>Caparrella</option>
                <option>Lladonosa</option>
            </select>
        </div>
        <br />
        <div class="d-flex justify-content-center">
        <button class="btn btn-outline-dark"><?= lang('crud.buttons.enter') ?></button>
        </div>
    </div>
</form>
<?= $this->endSection('contingut'); ?>