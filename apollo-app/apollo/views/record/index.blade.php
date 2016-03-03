<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.4
 */
use Apollo\Helpers\AssetHelper;

?>
@extends('layouts.extended')
@section('content')
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="#">All Records</a></li>
        <li role="presentation" class="active"><a href="#">Most viewed</a></li>
        <li role="presentation"><a href="#">Recently added</a></li>
        <li role="presentation"><a href="#">Recently updated</a></li>
    </ul>
    <div class="row">
        <div class="col-lg-8 col-md-6 col-sx-12 top-buffer">
            <div class="input-group">
                        <span class="input-group-addon" id="basic-addon2"><span
                                    class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                <input type="text" class="form-control" placeholder="Search through the records..."
                       aria-label="Quick Search">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-sx-12 top-buffer">
            <a href="search.php" class="btn btn-default btn-block">Advanced search</a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-sx-12 top-buffer">
            <a href="add.php" class="btn btn-default btn-block">Add a record</a>
        </div>
    </div>
    <div class="table-responsive top-buffer">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Given name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            </thead>
            <tbody id="table-body">
            </tbody>
        </table>
    </div>
    <nav class="text-center">
        <ul class="pagination" id="pagination">
        </ul>
    </nav>
@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/record/table') }}"></script>
@stop