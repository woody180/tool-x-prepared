<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= assetsUrl('css/uikit.min.css') ?>">
    <link rel="stylesheet" href="<?= assetsUrl('css/main.min.css') ?>">

    <script src="<?= assetsUrl('js/uikit.min.js') ?>"></script>
    <script src="<?= assetsUrl('js/uikit-icons.min.js') ?>"></script>
    
    <title><?= $title ?? APPNAME; ?></title>
</head>
<body>

    <?php $this->insert('partials/header') ?>