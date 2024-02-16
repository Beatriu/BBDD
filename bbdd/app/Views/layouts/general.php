<!--beatriu: layout general que s'executarà a la majoria de vistes.-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
    <title><?= esc($title) ?></title>
</head>

<body>
    <header class="d-flex justify-content-between" style="background-color: #333333;">
        <img class="logo" src="<?= base_url('img/Logotip/Logotip per aplicar a fons negres.png') ?>" />
        <?php if ($locale == 'es') { ?>
            <a><img class="imatge" src="<?= base_url('img/Banderes/Flag_of_Catalonia.png') ?>" /></a>
        <?php } else { ?>
            <a><img class="imatge" src="<?= base_url('img/Banderes/Bandera_de_España.png') ?>" /></a>
        <?php } ?>
    </header>
    <?= $this->renderSection('contingut') ?>
    <script <?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>></script>
</body>

</html>