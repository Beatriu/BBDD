<?= $this->extend('layouts/professors'); ?>

<?= $this->section('css_pagina'); ?>
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'formulari.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'taulaRegistre.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'header.css') ?>">
<link rel="stylesheet" href="<?= base_url('css' . DIRECTORY_SEPARATOR . 'style.css') ?>">
<?= $this->endSection('css_pagina'); ?>

<?= $this->section('header'); ?>
<?= $this->include('layouts/header.php'); ?>
<?= $this->endSection('header'); ?>

<?= $this->section('contingut'); ?>

<div class="container-fluid p-0 overflow-hidden">

    <div class="row">
        <!--Sidebar estàtic-->
        <div div class="col-sm-auto pl-0" id="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item" title="<?= lang("registre.dispositius_rebuts") ?>">
                    <a href="<?= base_url("/tiquets/emissor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.table-dispositius") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                        <i class="fa-solid fa-list-check"></i>
                    </a>
                </li>
                <li class="nav-item" title="<?= lang("registre.inventari") ?>">
                    <a href="<?= base_url("/inventari") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.inventari") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </a>
                </li>
                <?php if ($role == 'admin_sstt' || $role == 'desenvolupador') : ?>
                    <li class="nav-item" title="<?= lang("registre.alumnes") ?>">
                        <a href="<?= base_url("/alumnes") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.alumnes") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-users"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<?= base_url("/centres") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.centres") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                        <i class="fa-solid fa-school"></i>
                    </a>
                </li>
                <?php if ($role == 'admin_sstt' || $role == 'desenvolupador') : ?>
                    <li class="nav-item" title="<?= lang("registre.professors") ?>">
                        <a href="<?= base_url("/professor") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.professors") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-person-chalkboard"></i>
                        </a>
                    </li>
                    <li class="nav-item" title="<?= lang("registre.dades") ?>">
                        <a href="<?= base_url("/dades") ?>" id="actiu" class="nav-link py-3 px-2" title="<?= lang("registre.dades") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-chart-simple"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($role == 'desenvolupador') : ?>
                    <li class="nav-item" title="<?= lang("registre.tipus") ?>">
                        <a href="<?= base_url("/tipus/dispositiu") ?>" class="nav-link py-3 px-2" title="<?= lang("registre.tipus") ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                            <i class="fa-solid fa-gear"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>


        <div class="col-sm p-0 pb-3 ps-5 pe-5 min-vh-100" id="zona_taula">
            <div class="row mt-5 justify-content-center">
                <div class="col-2 d-flex align-items-center">
                    <a class="btn btn-dark rounded-pill" href="<?= base_url('/tiquets') ?>">
                        <i class="fa-solid fa-arrow-left"></i> <?= lang('general_lang.tornar') ?>
                    </a>
                </div>

                <div class="col-10 justify-content-left">
                    <h2><?= lang('dades.dades') ?></h2>
                </div>

            </div>


            <div>
                <?php if ((session()->get('centre_no_existeix')) !== null) : ?>
                    <div class="alert alert-warning alerta_esborrar" role="alert">
                        <?= session()->get('centre_no_existeix') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('llista_admesos_buit')) !== null) : ?>
                    <div class="alert alert-warning alerta_esborrar" role="alert">
                        <?= session()->get('llista_admesos_buit') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('llista_admesos_existeix')) !== null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= session()->get('llista_admesos_existeix') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('llista_admesos_esborrat')) !== null) : ?>
                    <div class="alert alert-danger alerta_esborrar" role="alert">
                        <?= session()->get('llista_admesos_esborrat') ?>
                    </div>
                <?php endif; ?>
                <?php if ((session()->get('llista_admesos_creat')) !== null) : ?>
                    <div class="alert alert-success alerta_esborrar" role="alert">
                        <?= session()->get('llista_admesos_creat') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row border mt-4 ms-1 me-0 pe-0 ps-0">
                <div class="row form_header p-3 ms-0">
                </div>
            </div>

            <form id="formulari_dades" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-2 mt-4" id="tipus_dades_div">
                        <select class="form-select" id="tipus_dades" name = "tipus_dades" onchange = "tipusDades();" required>
                            <option value=""><?= lang('dades.esculli_opcio') ?></option>
                            <option value="nombre_finalitzats" ><?= lang('dades.nombre_finalitzats') ?></option>
                            <option  value="nombre_emesos" ><?= lang('dades.nombre_emesos') ?></option>
                            <option  value="despeses" ><?= lang('dades.despeses') ?></option>
                            <option  value="nombre_finalitzats_temps" ><?= lang('dades.nombre_finalitzats_temps') ?></option>
                            <option  value="nombre_emesos_temps" ><?= lang('dades.nombre_emesos_temps') ?></option>
                            <option  value="despeses_temps" ><?= lang('dades.despeses_temps') ?></option>
                        </select>
                    </div>
                    <div class="col-2 mt-4 d-none" id="estat_div">
                        <select class="form-select" id="estat" name = "estat" required>
                            <option value="finalitzats" ><?= lang('dades.finalitzats') ?></option>
                            <option  value="retornats" ><?= lang('dades.retornats') ?></option>
                            <option  value="desguassats" ><?= lang('dades.desguassats') ?></option>
                            <option  value="rebutjats" ><?= lang('dades.rebutjats') ?></option>
                        </select>
                    </div>
                    <div class="col-2 mt-4 d-none" id="tipus_actor_div">
                        <select class="form-select" id="tipus_actor" name = "tipus_actor" required>
                            
                        </select>
                    </div>
                    <div class="col-2 mt-4 d-none" id="tipus_dispositiu_div">
                        <select class="form-select" id="tipus_dispositiu" name = "tipus_dispositiu" required>
                            <option  value="sense" > <?= lang('dades.sense_tipus') ?> </option>
                            <option  value="tots_separats" ><?= lang('dades.tots_separats') ?></option>
                            <?= $tipus_dispositiu ?>
                        </select>
                    </div>
                    <div class="col-4 mt-4 d-none" id="buttons_div">
                        <button type="submit" onclick="submitForm('/dades/descarregar')" class="btn btn-success rounded-pill"><i class="fa-solid fa-file-csv"></i> <?= lang('dades.descarregar_csv') ?></button>
                        <button type="submit" onclick="submitForm('/dades/visualitzar')" class="btn btn-info rounded-pill text-white"><i class="fa-solid fa-eye text-white"></i> <?= lang('dades.visualitzar') ?></button>
                    </div>
                </div>
            </form>


            <div class="row mt-4">
                <div class="col">
                    <?= $output ?>
                </div>
            </div>
            <?php if ($grafic != null || $grafic2 != null): ?>
                <div class="row mt-4">
                    <div class="col mb-3 d-flex align-items-middle" style="max-width: 100%; max-height: 400px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            <?php endif; ?>

        </div>



    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    <?php if ($grafic != null): ?>

        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
            labels: [<?= $grafic['labels'] ?>],
            datasets: [{
                label: '<?= $grafic['title'] ?>',
                data: [<?= $grafic['dades'] ?>],    
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                    ],
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });

    <?php endif; ?>

    <?php if ($grafic2 != null): ?>

        const ctx2 = document.getElementById('myChart');

        new Chart(ctx2, {
            type: 'line',
            data: {
            labels: [<?= $grafic2['labels'] ?>],
            datasets: [{
                label: '<?= $grafic2['title'] ?>',
                data: [<?= $grafic2['dades'] ?>],
                fill: false,
                borderColor: 'rgb(144, 0, 0)',
                tension: 0.1
            }]
            },
            options: {
                scales: {
                y: {
                    beginAtZero: true
                },
                x: [{
                    type: 'time',
                    time: {
                        parser: 'MM-YYYY',
                        unit: 'month',
                        displayFormats: {
                            month: 'MM-YYYY'
                        }
                    }
                }]
                }
            }
        });


    <?php endif; ?>
</script>
<script>    
    function submitForm(action) {
        var form = document.getElementById('formulari_dades');
        form.action = action;
    }

    function tipusDades()
    {
        let tipus_dades_select = document.getElementById("tipus_dades");
        let tipus_dades_value = tipus_dades_select.value;

        if (tipus_dades_value == "nombre_finalitzats") {

            document.getElementById("estat_div").classList.remove("d-none");
            document.getElementById("tipus_actor_div").classList.remove("d-none");
            document.getElementById("tipus_dispositiu_div").classList.remove("d-none");
            document.getElementById("buttons_div").classList.remove("d-none");

            document.getElementById("tipus_actor").innerHTML = <?= $tipus_actor_nombre_finalitzats ?>;

        } else if (tipus_dades_value == "nombre_emesos") {

            document.getElementById("estat_div").classList.add("d-none");
            document.getElementById("tipus_actor_div").classList.remove("d-none");
            document.getElementById("tipus_dispositiu_div").classList.remove("d-none");
            document.getElementById("buttons_div").classList.remove("d-none");

            document.getElementById("tipus_actor").innerHTML = <?= $tipus_actor_nombre_emesos ?>;

        } else if (tipus_dades_value == "despeses") {

            document.getElementById("estat_div").classList.add("d-none");
            document.getElementById("tipus_actor_div").classList.remove("d-none");
            document.getElementById("tipus_dispositiu_div").classList.remove("d-none");
            document.getElementById("buttons_div").classList.remove("d-none");

            document.getElementById("tipus_actor").innerHTML = <?= $tipus_actor_despeses ?>;

        } else if (tipus_dades_value == "nombre_finalitzats_temps") {

            document.getElementById("estat_div").classList.remove("d-none");
            document.getElementById("tipus_actor_div").classList.remove("d-none");
            document.getElementById("tipus_dispositiu_div").classList.add("d-none");
            document.getElementById("buttons_div").classList.remove("d-none");

            document.getElementById("tipus_actor").innerHTML = <?= $tipus_actor_nombre_finalitzats_temps ?>;

            } else if (tipus_dades_value == "nombre_emesos_temps") {

            document.getElementById("estat_div").classList.add("d-none");
            document.getElementById("tipus_actor_div").classList.remove("d-none");
            document.getElementById("tipus_dispositiu_div").classList.add("d-none");
            document.getElementById("buttons_div").classList.remove("d-none");

            document.getElementById("tipus_actor").innerHTML = <?= $tipus_actor_nombre_emesos_temps ?>;

            } else if (tipus_dades_value == "despeses_temps") {

            document.getElementById("estat_div").classList.add("d-none");
            document.getElementById("tipus_actor_div").classList.remove("d-none");
            document.getElementById("tipus_dispositiu_div").classList.add("d-none");
            document.getElementById("buttons_div").classList.remove("d-none");

            document.getElementById("tipus_actor").innerHTML = <?= $tipus_actor_despeses_temps ?>;

            } else {
            
            document.getElementById("estat_div").classList.add("d-none");
            document.getElementById("tipus_actor_div").classList.add("d-none");
            document.getElementById("tipus_dispositiu_div").classList.add("d-none");
            document.getElementById("buttons_div").classList.add("d-none");

        }
    }
</script>
<?= $this->endSection('contingut'); ?>