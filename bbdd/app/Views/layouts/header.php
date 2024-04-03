<?= $this->section('header'); ?>
<header class="d-flex bd-highlight" style="background-color: #333333;">
    <div class="p-2 flex-grow-1 bd-highlight">
        <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />
    </div>
    <div class="p-2 bd-highlight d-flex">
        <a><img class="ms-auto imatge" src="<?= base_url(lang('general_lang.banderilla')) ?>" /></a>

        <div class="dropdown show">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dropdown link
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
            </div>
        </div>
    </div>


    <!--<select class="form-select rounded-pill user_select me-3" aria-label="Default select example">
            <option class="name_option" selected>NOM</option>
            <option value="1"><?= lang('general_lang.tancar') ?></option>
        </select> -->

</header>
<?= $this->endSection('header'); ?>