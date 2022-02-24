<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <script src="https://kit.fontawesome.com/35f0eb7d27.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300;400,500,600,700,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/style.css" />
    <title><?php echo SITENAME; ?></title>
</head>

<body>
    <!-- TOP NAVIGATION -->
    <nav class="navbar-container">
        <div class="navbar">
            <div class="logo"><img src="<?php echo URLROOT ?>/public/assets/icon.png" alt="Logo" class="logo-img" />
                <p class="logo-text">REMINDER</p>
                <hr>
            </div>

            <div class="moto">
                <h4>NEVER FORGET AGAIN</h4>
                <h6>"Get reminded with an Email"</h6>
            </div>
            <i class="humburger-menu fa-solid fa-bars"></i>
        </div>
        <hr class="header-end">
    </nav>
    <section class="side-navbar hidden">
        <div class="">
        <a href="<?php echo URLROOT ?>/Users/login"><?php
        if($_SERVER['REQUEST_URI'] == "/reminder/Users/login" ||$_SERVER['REQUEST_URI'] == "/reminder/Users/register")
        {echo "Login";}else{ echo "Logout";}?></a></div>
    </section>
