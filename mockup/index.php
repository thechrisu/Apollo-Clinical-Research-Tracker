<?php

$title = 'Sign In';
$hide_nav = true;
$page = 'sign-in';
include 'templates/header.php';

?>

    <h1 class="text-center">Apollo</h1>

    <div class="row">

        <div class="col-md-4 col-md-offset-4 col-sm-12 col-sm-offset-0">

            <div class="panel panel-default">
                <div class="panel-heading">Please sign in:</div>
                <div class="panel-body">

                    <form>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon2"><span
                                            class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                                    <input type="email" class="form-control" placeholder="john.smith@example.co.uk"
                                           aria-label="Email">
                                </div>
                            </div>
                        </div>

                        <div class="row top-buffer">
                            <div class="col-md-12">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"
                                                                          aria-hidden="true"></span></span>
                                    <input type="password" class="form-control" placeholder="*************"
                                           aria-label="Password">
                                </div>
                            </div>
                        </div>

                        <div class="row top-buffer">

                            <div class="col-md-8"><a href="#" class="btn btn-default">Forgot your password?</a></div>

                            <div class="col-md-4">
                                <!--<button type="submit" class="btn btn-primary btn-block">Sign in</button>-->
                                <a href="records.php" class="btn btn-primary btn-block">Sign in</a>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>

<?php
$no_copy = true;
include 'templates/footer.php';
?>
