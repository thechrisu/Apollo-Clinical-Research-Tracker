<?php

$title = 'Manage users';
$page = 'users';
include 'templates/header.php';

?>

<div class="panel panel-default">

    <div class="panel-heading">
        <ol class="breadcrumb" id="nav-breadcrumbs">
            <li>Apollo</li>
            <li class="active">Manage users</li>
        </ol>
    </div>

    <div class="panel-body">

        <div class="responsive-table">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Last activity</th>
                <th class="text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Chris Ulshoefer</td>
                <td>chris@ulshoefer.de</td>
                <td>November 6, 2015 12:50 am</td>
                <td class="text-right">
                    <button class="btn btn-default btn-sm" style="padding: 3px 9px !important;">Reset Password</button>
                    <button class="btn btn-default btn-sm" style="padding: 3px 9px !important;">View log</button>
                    <button class="btn btn-default btn-sm" style="padding: 3px 9px !important;">Remove</button>
                </td>
            </tr>
            </tbody>
        </table>
        </div>

    </div>

</div>

<?php
include 'templates/footer.php';
?>
