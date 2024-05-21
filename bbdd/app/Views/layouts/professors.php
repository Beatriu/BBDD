<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?//= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/sidebar.css')?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title><?= esc($title) ?></title>
    <?= $this->renderSection('css_pagina') ?>
</head>

<body>
    <?= $this->renderSection('header') ?>

    <?= $this->renderSection('contingut') ?>
    <script src="<?//= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://kit.fontawesome.com/7f13a820d7.js" crossorigin="anonymous"></script>
    <script src="<?//= base_url('js/bbdd/public/js/main_sidebar.js') ?>"></script>
</body>

</html>