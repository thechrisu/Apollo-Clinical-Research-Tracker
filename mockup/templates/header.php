<?php
/**
 * Created by PhpStorm.
 * User: timbokz
 * Date: 02/02/16
 * Time: 14:11
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title><?= $title ?> | Apollo</title>

    <script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://chancejs.com/chance.min.js"></script>

</head>
<body<?= isset($page) ? ' id="' . $page . '"' : '' ?>>

<div class="container" id="main-container">

    <?php
    if (!isset($hide_nav)) :
        ?>

        <nav class="navbar navbar-default top-buffer">

            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Apollo</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-left">
                        <p class="navbar-text">Signed in as Timur Kuzhagaliyev</p>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php $active = ' class="active"'; ?>
                        <li<?= $page == 'record' || $page == 'records' ? $active : '' ?>><a href="records.php">Records</a></li>
                        <li<?= $page == 'programme' || $page == 'programmes' ? $active : '' ?>><a href="programmes.php">Programmes</a></li>
                        <li<?= $page == 'field' || $page == 'fields' ? $active : '' ?>><a href="fields.php">Fields</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true"
                               aria-expanded="false">User Actions <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Edit Settings</a></li>
                                <li><a href="#">Manage users</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Sign out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

        </nav>
    <?php
    endif;
    ?>
