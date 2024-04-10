<?= $this->section('header'); ?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<header class="d-flex bd-highlight" style="background-color: #333333;">
    <div class="p-2 flex-grow-1 bd-highlight">
        <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />
    </div>
    <div class="p-2 bd-highlight d-flex pe-4">

        <a class="me-3" href="<?= base_url('/canviLanguage') ?>"><img class="ms-auto imatge" src="<?= base_url(lang('general_lang.banderilla')) ?>" /></a>

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle rounded-pill" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= session()->get('user_data')['nom'] ?>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left mt-3" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i><?= lang('general_lang.config') ?></a>
                <a class="dropdown-item" href="<?= base_url('/logout') ?>"><i class="fa-solid fa-power-off me-2"></i><?= lang('general_lang.tancar') ?></a>
            </div>
        </div>

    </div>

</header>
<?= $this->endSection('header'); ?>