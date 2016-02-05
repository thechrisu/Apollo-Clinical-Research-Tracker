<?php

$title = 'Programmes';
$page = 'programmes';
include 'templates/header.php';
?>

    <div class="panel panel-default">

        <div class="panel-heading">
            <ol class="breadcrumb" id="nav-breadcrumbs">
                <li>Apollo</li>
                <li class="active">Programmes</li>
            </ol>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-2 col-md-offset-10">
                    <a href="search.html" class="btn btn-default btn-block">Add a record</a>
                </div>
            </div>
            <div class="table-responsive top-buffer">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Programme name</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Target group</th>
                        <th>Funding</th>
                    </tr>
                    </thead>
                    <tbody id="table-body">
                    <tr>
                        <td>Curing cancer</td>
                        <td>01 Apr 2010</td>
                        <td>01 Apr 2042</td>
                        <td>PhD students</td>
                        <td>Kickstarter</td>
                    </tr><tr>
                        <td>Editing genes</td>
                        <td>01 Feb 2016</td>
                        <td>24 Dec 2017</td>
                        <td>NHS</td>
                        <td>Francis Crick</td>
                    </tr><tr>
                        <td>Mapping the human brain</td>
                        <td>25 Jun 2013</td>
                        <td>13 Jun 2017</td>
                        <td>Neuroscientists</td>
                        <td>Skynet</td>
                    </tr><tr>
                        <td>Trial of Perioperative Endocrine Therapy - Individualising Care</td>
                        <td>02 Feb 2016</td>
                        <td>12 Sep 2017</td>
                        <td>Oncologists</td>
                        <td>Institute of Cancer Research UK</td>
                    </tr><tr>
                        <td>Rapid Assessment of Potential Ischaemic Heart Disease With CTCA</td>
                        <td>01 Mar 2016</td>
                        <td>12 Sep 2017</td>
                        <td>XYZ</td>
                        <td>University College London Fellowship Fund</td>
                    </tr>
                    </tbody>
                </table>
            </div>
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

    <script>
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