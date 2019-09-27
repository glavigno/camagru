<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $title ?></title>
        <link rel="stylesheet" type="text/css" href="public/css/style.css">
        <link rel="stylesheet" type="text/css" href="public/css/navbar.css">
        <link rel="stylesheet" type="text/css" href="public/css/login.css">
        <link rel="stylesheet" type="text/css" href="public/css/register.css">
        <link rel="stylesheet" type="text/css" href="public/css/main.css">
        <link rel="stylesheet" type="text/css" href="public/css/admin.css">
        <link rel="stylesheet" type="text/css" href="public/css/grid.css">
        <link rel="stylesheet" type="text/css" href="public/css/post.css">
        <link rel="stylesheet" type="text/css" href="public/css/reset.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    </head>
    <body>
        <div class="topnav">
            <a href="<?= URL ?>?url=gallery">
                <div class="navblock">
                    <i class="fas fa-th fa-2x"></i>
                    <p>Gallery</p>
                </div>
            </a>
            <?php session_start(); ?>
            <?php if (!isset($_SESSION['user'])): ?>
            <a href="<?= URL ?>?url=login">
                <div class="navblock">
                    <i class="fas fa-sign-in-alt fa-2x"></i>
                    <p>Login</p>
                </div>
            </a>
            <a href="<?= URL ?>?url=register">
                <div class="navblock">
                    <i class="fas fa-user-plus fa-2x"></i>
                    <p>Register</p>
                </div>
            </a>
            <?php else: ?>
            <a href="<?= URL ?>?url=main">
                <div class="navblock">
                    <i class="fas fa-person-booth fa-2x"></i>
                    <p>Booth</p>
                </div>
            </a>
            <a href="<?= URL ?>?url=admin">
                <div class="navblock">
                    <i class="fas fa-user-cog fa-2x"></i>
                    <p>Admin</p>
                </div>
            </a>
            <a href="<?= URL ?>?url=logout">
                <div class="navblock">
                    <i class="fas fa-sign-out-alt fa-2x"></i>
                    <p>Log out</p>
                </div>
            </a>
            <?php endif; ?>
        </div>
        <div class="container">
            <?= $content ?>
        </div>
    </body>
</html>