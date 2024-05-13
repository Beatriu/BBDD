<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?//= base_url('css/sidebar.css')?>">
    <title><?= esc($title) ?></title>
    <?= $this->renderSection('css_pagina') ?>
</head>

<body>
    <?= $this->renderSection('header') ?>

    <?= $this->renderSection('contingut') ?>
    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://kit.fontawesome.com/7f13a820d7.js" crossorigin="anonymous"></script>
    <script src="<?//= base_url('js/bbdd/public/js/main_sidebar.js') ?>"></script>
</body>

</html>