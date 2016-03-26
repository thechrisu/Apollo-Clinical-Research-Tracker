<?php

$title = 'Edit activity';
$page = 'activity';
include 'templates/header.php';
?>
<!--
TODO:
a lot.
Options for dropdown-menu for funding to allow-others
does this view get accessed by only adding new programmes or also editing existing ones?
-->

    <div class="panel panel-default">

        <div class="panel-heading">
            <ol class="breadcrumb" id="nav-breadcrumbs">
                <li>Apollo</li>
                <li class="active">Edit programme</li>
            </ol>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 top-buffer">
                    <div class="row">
                    <label for="newProgramme" class="col-md-2 control-label">Title</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="newProgramme" placeholder="Programme title">
                    </div>
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 top-buffer">
                    <div class="row" style="padding-right: 15px">
                    <div class="col-md-2">
                        <label for="targetGroupDropdown" class="control-label">Target group</label>
                    </div>
                    <div class="col-md-4" id="targetGroupDropdown">
                        <select class="form-control">
                            <option>PhD</option>
                            <option>Nobel laureate</option>
                            <option>This is a very long option, to be honest</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="startDatePicker" class="control-label">Start date</label>
                    </div>
                    <div class="col-md-4 input-group date" data-provide="datepicker" id="startDatePicker">
                        <input class="form-control" type="text" placeholder="Start date">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 top-buffer">
                    <div class="row" style="padding-right: 15px">
                    <div class="col-md-2">
                        <label for="fundinngText">Funding</label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="fundingText" placeholder="Funding">
                    </div>
                    <label for="endDatePicker" class="col-md-2 control-label">End date </label>
                    <div class="col-md-4 input-group date" data-provide="datepicker" id="endDatePicker">
                        <input class="form-control" type="text" placeholder="End date">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="row top-buffer">
                <div class="col-md-6">
                    <h3>Current participants:</h3>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-8">
                    <select class="form-control">
                        <option>Add a participant...</option>
                        <option>Chris</option>
                        <option>Desi</option>
                        <option>Tim</option>
                    </select>
                        </div>
                        <div class="col-md-4">
                    <button class="btn btn-default btn-block">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive top-buffer">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                        <td>123</td>
                        <td>Tim</td>
                        <td>Kuzhagaliyev</td>
                        <td>tim.kuzh@gmail.com</td>
                        <td><button class="btn-remove-field btn btn-default btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
                    </tbody>
                </table>
            </div>

            <div class="text-center top-buffer">

                <button class="btn btn-default btn-lg pull-right">Save changes</button>

            </div>

        </div>
    </div>

    <script>
        $('.datepicker').datepicker();
        $(document).ready(function () {
            var tableBody = $('#table-body');
            tableBody.on('click', 'tr', function () {
                window.location = 'programme.php';
            })
        });
    </script>

<?php

include 'templates/footer.php';
?>