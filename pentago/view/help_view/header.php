<?php
require_once '../component/default_component.php';
require_once '../component/auto_login_component.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?=$DOMAIN_PATH?>/css/reset.css">
    <link rel="stylesheet" href="<?=$DOMAIN_PATH?>/css/new.css">
    <?php if (isset($need_awesome_font) && $need_awesome_font){?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
    <?php }?>
    <title>Pentago</title>
</head>
<body>
<div class="side-nav">
    <div class="closebtn">&times;</div>
    <div class="logo">Play Pentago</div>
    <a href="<?=$DOMAIN_PATH?>/main">Main</a>
    <a href="<?=$DOMAIN_PATH?>/account">My account</a>
    <a href="" class="modal-btn" id="btn-modal-1">Login</a>
    <a href="<?=$DOMAIN_PATH?>/friends">Friends</a>
    <a href="<?=$DOMAIN_PATH?>/rules">Rules</a>
    <div class="author">Made by Peter Shpis</div>
</div>

<div class="top-nav">
    <div class="burger">&#9776;</div>
    <div class="logo">Play Pentago</div>
</div>