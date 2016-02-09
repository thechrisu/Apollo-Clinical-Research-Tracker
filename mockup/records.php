<?php

$title = 'Records';
$page = 'records';
include 'templates/header.php';
?>

    <div class="panel panel-default">

        <div class="panel-heading">
            <ol class="breadcrumb" id="nav-breadcrumbs">
                <li>Apollo</li>
                <li class="active">Records</li>
            </ol>
        </div>

        <div class="panel-body">

            <ul class="nav nav-tabs">
                <li role="presentation"><a href="#">All Records</a></li>
                <li role="presentation" class="active"><a href="#">Most viewed</a></li>
                <li role="presentation"><a href="#">Recently added</a></li>
                <li role="presentation"><a href="#">Recently updated</a></li>
            </ul>

            <div class="row top-buffer">

                <div class="col-md-8">

                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon2"><span
                                class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                        <input type="text" class="form-control" placeholder="Search through the records..." aria-label="Quick Search">
                    </div>

                </div>

                <div class="col-md-2">

                    <a href="search.php" class="btn btn-default btn-block">Advanced search</a>

                </div>

                <div class="col-md-2">

                    <a href="add.php" class="btn btn-default btn-block">Add a record</a>

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