<?php

$title = 'Charlotte Warren-Gash | Record #531';
$page = 'record';
include 'templates/header.php';

?>

    <div class="panel panel-default">

        <div class="panel-heading">
            <a href="edit.php" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-pencil"
                                                                                aria-hidden="true"></span> &nbsp;&nbsp;&nbsp;Edit
                this record</a>
            <ol class="breadcrumb" id="nav-breadcrumbs">
                <li>Apollo</li>
                <li><a href="record.php">Records</a></li>
                <li class="active"><i>Record #531</i> Charlotte Warren-Gash</li>
            </ol>
        </div>

        <div class="panel-body">

            <div class="alert alert-success alert-dismissable">

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>

                &nbsp;&nbsp;&nbsp;&nbsp;You have successfully updated the record #531 belonging to Charlotte
                Warren-Gash.

            </div>

            <div class="row">
                <div class="col-md-3">
                    <img src="images/record.png" class="img-thumbnail">
                </div>
                <div class="col-md-5">
                    <h3>Charlotte
                        <small>First Name</small>
                    </h3>
                    <h3>Warren-Gash
                        <small>Surname</small>
                    </h3>
                    <h3>c.warren-gash@ucl.ac.uk
                        <small>Email</small>
                    </h3>
                    <h3>07894 664 278
                        <small>Phone</small>
                    </h3>
                </div>
                <div class="col-md-4">
                    <h3>
                        <small>Address:</small>
                    </h3>
                    <h3>Institute of Child Health</h3>
                    <h3>UCL, Gower Street</h3>
                    <h3>WC1E 6BT, London, UK</h3>
                </div>
            </div>

            <div class="row top-buffer">

                <div class="col-md-4">

                    <div class="table-responsibe">

                        <table class="table">

                            <caption>Funding</caption>

                            <tbody>

                            <tr>
                                <td>Funding Source:</td>
                                <td>Deanery</td>
                            </tr>

                            <tr>
                                <td>Funding Category:</td>
                                <td>Deanery</td>
                            </tr>

                            <tr>
                                <td>Pay Band:</td>
                                <td>9</td>
                            </tr>

                            </tbody>

                        </table>

                    </div>

                    <div class="table-responsibe">

                        <table class="table">

                            <caption>Speciality</caption>

                            <tbody>

                            <tr>
                                <td>Clinical Speciality:</td>
                                <td>Public Health Medicine</td>
                            </tr>

                            <tr>
                                <td>HRCS Health Category:</td>
                                <td>Population Health</td>
                            </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="table-responsibe">

                        <table class="table">

                            <caption>Research Activity</caption>

                            <tbody>

                            <tr>
                                <td>HRCS Research Activity Codes:</td>
                                <td>Epidemiology</td>
                            </tr>

                            <tr>
                                <td>Research Area:</td>
                                <td>Epidemiology</td>
                            </tr>

                            <tr>
                                <td>Start Date:</td>
                                <td>April 1st, 2014</td>
                            </tr>

                            <tr>
                                <td>End Date:</td>
                                <td>March 31st, 2018</td>
                            </tr>

                            <tr>
                                <td>Supervisor:</td>
                                <td>Professor Geraint Rees</td>
                            </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="table-responsibe">

                        <table class="table">

                            <caption>Other Info</caption>

                            <tbody>

                            <tr>
                                <td>NHS Trust</td>
                                <td>GOSH</td>
                            </tr>

                            <tr>
                                <td>Next Destination:</td>
                                <td class="secondary-text">Unknown</td>
                            </tr>

                            <tr>
                                <td>Previous Post:</td>
                                <td>Clinical Research Training Fellow</td>
                            </tr>

                            <tr>
                                <td>Highest Degree Type:</td>
                                <td>PhD</td>
                            </tr>

                            <tr>
                                <td>PhD Title:</td>
                                <td>Some PhD Title</td>
                            </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <div class="row top-buffer">

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Publications</th>
                            </tr>
                            </thead>
                            <tbody id="table-body-1">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Awards</th>
                            </tr>
                            </thead>
                            <tbody id="table-body-2">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Programmes</th>
                            </tr>
                            </thead>
                            <tbody id="table-body-3">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>


    <script>
        $(document).ready(function () {
            var chance = new Chance(Math.random);
            for (var k = 1; k < 4; k++) {
                var tableBody = $('#table-body-' + k);
                for (var i = 0; i < 4; i++) {
                    var tr = $('<tr></tr>');
                    tr.append($('<td>' + (k == 1 ? chance.google_analytics() : (k == 2 ? chance.hash({length: 10}) : chance.ssn())) + '</td>'));
                    tableBody.append(tr);
                }
                tableBody.on('click', 'tr', function () {
                    window.location = 'record.php';
                });
            }
        });
    </script>

<?php
include 'templates/footer.php';
?>