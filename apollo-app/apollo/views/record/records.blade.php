<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.3
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
    <div class="row top-buffer">
        <div class="col-md-8">
            <div class="input-group">
                        <span class="input-group-addon" id="basic-addon2"><span
                                    class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                <input type="text" class="form-control" placeholder="Search through the records..."
                       aria-label="Quick Search">
            </div>
        </div>
        <div class="col-md-2">
            <a href="search.php" class="btn btn-default btn-block">Advanced search</a>
        </div>
        <div class="col-md-2">
            <a href="add.php" class="btn btn-default btn-block">Add a record</a>
        </div>
    </div>
    <div class="table-responsive top-buffer" id="personTable">

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
@stop
@section('scripts')
    @parent
    <script type="text/babel" src="{{ AssetHelper::js("PersonTablePagination") }}"></script>
@stop