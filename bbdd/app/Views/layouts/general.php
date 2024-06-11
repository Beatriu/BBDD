<!--beatriu: layout general que s'executarà a la majoria de vistes.-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title><?= esc($title) ?></title>
    <?= $this->renderSection('css_pagina') ?>
    <script src="https://kit.fontawesome.com/7f13a820d7.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="<?= base_url("img/Logotip/Logotip per aplicar a fons en blanc.png") ?>">
</head>

<body>
    <?= $this->renderSection('header') ?>

    <?= $this->renderSection('contingut') ?>
    <script <?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>></script>
</body>

</html>