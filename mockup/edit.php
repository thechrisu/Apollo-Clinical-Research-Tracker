<?php

$title = 'Edit | Charlotte Warren-Gash | Record #531';
$page = 'edit-record';
include 'templates/header.php';

?>

    <div class="panel panel-default">

        <div class="panel-heading">
            <a href="record.php" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-ok"
                                                                                 aria-hidden="true"></span> &nbsp;&nbsp;&nbsp;Done
            </a>
            <ol class="breadcrumb" id="nav-breadcrumbs">
                <li>Apollo</li>
                <li><a href="record.php">Records</a></li>
                <li class="active"><i>Record #531</i> Charlotte Warren-Gash</li>
            </ol>
        </div>

        <div class="panel-body">

            <div class="row">
                <div class="col-md-3">
                    <img src="images/record.png" class="img-thumbnail">
                </div>
                <div class="col-md-5">
                    <div class="input-group top-buffer">
                        <input type="text" class="form-control large-font" placeholder="First name" value="Charlotte"
                               aria-label="Edit">
                    </div>
                    <div class="input-group top-buffer">
                        <input type="text" class="form-control large-font" placeholder="Surname" value="Warren-Gash"
                               aria-label="Edit">
                    </div>
                    <div class="input-group top-buffer">
                        <input type="text" class="form-control large-font" placeholder="Email"
                               value="c.warren-gash@ucl.ac.uk" aria-label="Edit">
                    </div>
                    <div class="input-group top-buffer">
                        <input type="text" class="form-control large-font" placeholder="Phone" value="07894 664 278"
                               aria-label="Edit">
                    </div>
                </div>
                <div class="col-md-4">
                    <h3>
                        <small>Address:</small>
                    </h3>
                    <div class="input-group top-buffer">
                        <input type="text" class="form-control" placeholder="Address 1"
                               value="Institute of Child Health" aria-label="Edit">
                    </div>
                    <div class="input-group top-buffer">
                        <input type="text" class="form-control" placeholder="Address 2" value="UCL, Gower Street"
                               aria-label="Edit">
                    </div>
                    <div class="input-group top-buffer">
                        <input type="text" class="form-control" placeholder="Address 3" value="WC1E 6BT, London, UK"
                               aria-label="Edit">
                    </div>
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
                                <td>
                                    <input type="text" class="form-control small-table" placeholder="Funding source"
                                           value="None" aria-label="Edit">
                                </td>
                            </tr>

                            <tr>
                                <td>Funding Category:</td>
                                <td>
                                    <select class="form-control small-table">
                                        <option>Deanery</option>
                                        <option>Nothing</option>
                                        <option>Something</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Pay Band:</td>
                                <td>
                                    <select class="form-control small-table">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>9</option>
                                    </select>
                                </td>
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
                                <td>
                                    <select class="form-control small-table">
                                        <option>Public Health Medicine</option>
                                        <option>Something</option>
                                        <option>Something else</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>HRCS Health Category:</td>
                                <td>
                                    <select class="form-control small-table">
                                        <option>Population Health</option>
                                        <option>Something</option>
                                        <option>Something else</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>HRCS Health Category (2):</td>
                                <td>
                                    <select class="form-control small-table">
                                        <option>Population Health</option>
                                        <option>Something</option>
                                        <option>Something else</option>
                                    </select>
                                </td>
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
                                <td>
                                    <select class="form-control small-table">
                                        <option>Epidemiology</option>
                                        <option>Something</option>
                                        <option>Something else</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>HRCS Research Activity Codes (2):</td>
                                <td>
                                    <select class="form-control small-table">
                                        <option>Epidemiology</option>
                                        <option>Something</option>
                                        <option>Something else</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Research Area:</td>
                                <td>
                                    <input type="text" class="form-control small-table input-sm"
                                           placeholder="Research Area" value="None" aria-label="Edit">
                                </td>
                            </tr>

                            <tr>
                                <td>Start Date:</td>
                                <td>
                                    <div class="input-group date" data-provide="datepicker" id="startDatePicker">
                                        <input class="form-control small-table" type="text" placeholder="Start date"
                                               value="02/17/2016">
                                        <div class="input-group-addon small-table"
                                             style="height: 14px !important; line-height: 14px !important;">
                                            <span class="glyphicon glyphicon-th small-table"
                                                  style="font-size: 12px !important; height: 14px !important; line-height: 14px !important;"></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>End Date:</td>
                                <td>
                                    <div class="input-group date" data-provide="datepicker" id="startDatePicker">
                                        <input class="form-control small-table" type="text" placeholder="Start date"
                                               value="02/17/2016">
                                        <div class="input-group-addon small-table"
                                             style="height: 14px !important; line-height: 14px !important;">
                                            <span class="glyphicon glyphicon-th small-table"
                                                  style="font-size: 12px !important; height: 14px !important; line-height: 14px !important;"></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>Supervisor:</td>
                                <td>
                                    <input type="text" class="form-control small-table input-sm"
                                           placeholder="Supervisor" value="Professor Geraint Rees" aria-label="Edit">
                                </td>
                            </tr>

                            <tr>
                                <td>Supervisor (2):</td>
                                <td>
                                    <input type="text" class="form-control small-table input-sm"
                                           placeholder="Supervisor" value="Professor Geraint Rees" aria-label="Edit">
                                </td>
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
                                <td>
                                    <input type="text" class="form-control small-table input-sm" placeholder="NHS Trust"
                                           value="GOSH" aria-label="Edit">
                                </td>
                            </tr>

                            <tr>
                                <td>Next Destination:</td>
                                <td>
                                    <input type="text" class="form-control small-table input-sm"
                                           placeholder="Next destination" value="Unknown" aria-label="Edit">
                                </td>
                            </tr>

                            <tr>
                                <td>Previous Post:</td>
                                <td>
                                    <input type="text" class="form-control small-table input-sm"
                                           placeholder="Previous post" value="Clinical Research Training Fellow"
                                           aria-label="Edit">
                                </td>
                            </tr>

                            <tr>
                                <td>Highest Degree Type:</td>
                                <td>
                                    <select class="form-control small-table">
                                        <option>PhD</option>
                                        <option>Nobel laureate</option>
                                        <option>This is a very long option, to be honest</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>PhD Title:</td>
                                <td>
                                    <input type="text" class="form-control small-table input-sm" placeholder="PhD title"
                                           value="Some PhD Title" aria-label="Edit">
                                </td>
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
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="table-body-1">
                            <tr>
                                <td>
                                    <input class="form-control input-sm" placeholder="Add...">
                                </td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                </td>
                            </tr>
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
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="table-body-2">
                            <tr>
                                <td>
                                    <input class="form-control input-sm" placeholder="Add...">
                                </td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                </td>
                            </tr>
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
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="table-body-3">
                            <tr>
                                <td>
                                    <input class="form-control input-sm" placeholder="Add...">
                                </td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                </td>
                            </tr>
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
                    tr.append($('<td width="90%">' + (k == 1 ? chance.google_analytics() : (k == 2 ? chance.hash({length: 10}) : chance.ssn())) + '</td>'));
                    tr.append($('<td width="10%" class="text-right"><div class="btn btn-default btn-sm" style="padding: 1px 4px !important;"><span class=\"glyphicon glyphicon-remove\"></span></div></td>'));
                    tableBody.prepend(tr);
                }
            }
        });
    </script>

<?php
include 'templates/footer.php';
?>