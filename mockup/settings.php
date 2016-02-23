<?php

$title = 'Settings';
$page = 'Settings';
include 'templates/header.php';

?>

<div class="panel panel-default">

    <div class="panel-heading">
        <ol class="breadcrumb" id="nav-breadcrumbs">
            <li>Apollo</li>
            <li class="active">Settings</li>
        </ol>
    </div>

    <div class="panel-body">

        <div class="panel panel-default">

            <div class="panel-heading">Personal information:</div>

            <div class="panel-body">

                <div class="row">
                    <div
                        class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Your first name:</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="e.g. John">
                        </div>
                    </div>
                    <div
                        class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="exampleInputEmail2">Your surname:</label>
                            <input type="text" class="form-control" id="exampleInputEmail2" placeholder="e.g. Doe">
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="panel panel-default">

            <div class="panel-heading">New password:</div>

            <div class="panel-body">

                <div class="row">
                    <div
                        class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Enter your new password:</label>
                            <input type="password" class="form-control" id="exampleInputEmail1" placeholder="******">
                        </div>
                    </div>
                    <div
                        class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="exampleInputEmail2">Confirm password:</label>
                            <input type="password" class="form-control" id="exampleInputEmail2" placeholder="******">
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="panel panel-default">

            <div class="panel-heading">Confirm changes:</div>

            <div class="panel-body">

                <div class="row">
                    <div
                        class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label for="exampleInputEmail3">Type in your current password:</label>
                            <input type="password" class="form-control" id="exampleInputEmail3" placeholder="******">
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="form-group">
            <button class="btn btn-default">Update your settings</button>
        </div>

    </div>

</div>

<?php
include 'templates/footer.php';
?>
