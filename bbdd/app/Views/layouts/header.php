<?= $this->section('header'); ?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<header class="d-flex bd-highlight" style="background-color: #333333;">
    <div class="p-2 flex-grow-1 bd-highlight">
        <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />
    </div>
    <div class="p-2 bd-highlight d-flex pe-5 me-2">

        <a class="me-3"><img class="ms-auto imatge" src="<?= base_url(lang('general_lang.banderilla')) ?>" /></a>

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                USUARI
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left mt-3" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#"><?= lang('general_lang.config') ?></a>
                <a class="dropdown-item" href="#"><?= lang('general_lang.tancar') ?></a>
            </div>
        </div>

    </div>


    <!--<select class="form-select rounded-pill user_select me-3" aria-label="Default select example">
            <option class="name_option" selected>NOM</option>
            <option value="1"><?= lang('general_lang.tancar') ?></option>
        </select> -->

</header>
<?= $this->endSection('header'); ?>