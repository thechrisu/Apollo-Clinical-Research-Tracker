<?php

$title = 'Advanced Search';
$page = 'search';
include 'templates/header.php';
?>

    <div class="panel panel-default">

        <div class="panel-heading">
            <ol class="breadcrumb" id="nav-breadcrumbs">
                <li>Apollo</li>
                <li class="active">Advanced Search</li>
            </ol>
        </div>

        <div class="panel-body">

            <h3>Search filters</h3>

            <div class="well">

                <div class="row">

                    <div class="col-md-3">
                        <select class="form-control">
                            <option>Include records where</option>
                            <option>Exclude records where</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="form-control">
                            <option>First name</option>
                            <option>Middle name</option>
                            <option>Surname</option>
                            <option>Funding</option>
                            <option>Funding source</option>
                            <option>PhD</option>
                            <option>PhD start date</option>
                            <option>PhD end date</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control">
                            <option>Is not empty</option>
                            <option>Is less than</option>
                            <option>Is greater than</option>
                            <option>Is equal to</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" placeholder="Value">
                    </div>
                    <div class="col-md-1 text-center">
                        <button class="btn btn-default btn-block"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
                    </div>
                    <div class="col-md-1 text-center">
                        <button class="btn btn-default btn-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                    </div>

                </div>

                <div class="row top-buffer">

                    <div class="col-md-3">
                        <select class="form-control">
                            <option>Include records where</option>
                            <option>Exclude records where</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="form-control">
                            <option>First name</option>
                            <option>Middle name</option>
                            <option>Surname</option>
                            <option>Funding</option>
                            <option>Funding source</option>
                            <option>PhD</option>
                            <option>PhD start date</option>
                            <option>PhD end date</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control">
                            <option>Is not empty</option>
                            <option>Is less than</option>
                            <option>Is greater than</option>
                            <option>Is equal to</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" placeholder="Value">
                    </div>
                    <div class="col-md-1 text-center">
                        <button class="btn btn-default btn-block"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
                    </div>
                    <div class="col-md-1 text-center">
                        <button class="btn btn-default btn-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                    </div>

                </div>

            </div>

            <div class="table-responsive top-buffer">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>First name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                    </thead>
                    <tbody id="table-body">
                    </tbody>
                </table>
            </div>

            <nav class="text-center">
                <ul class="pagination">
                    <li>
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li>
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

    </div>


    <script>
        $(document).ready(function () {
            var tableBody = $('#table-body');
            var chance = new Chance(Math.random);
            for (var i = 0; i < 10; i++) {
                var tr = $('<tr></tr>');
                tr.append($('<td>' + chance.first() + '</td>'));
                tr.append($('<td>' + chance.last() + '</td>'));
                tr.append($('<td>' + chance.email() + '</td>'));
                tr.append($('<td>' + chance.phone() + '</td>'));
                tableBody.append(tr);
            }
            tableBody.on('click', 'tr', function () {
                window.location = 'record.php';
            })
        });
    </script>


<?php

include 'templates/footer.php';
?>