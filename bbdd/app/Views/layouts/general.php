<!--beatriu: layout general que s'executarà a la majoria de vistes.-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <title><?= esc($title) ?></title>
    <?= $this->renderSection('css_pagina') ?>
</head>

<body>
    <?= $this->renderSection('header') ?>

    <?= $this->renderSection('contingut') ?>
    <script <?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>></script>
</body>

</html>