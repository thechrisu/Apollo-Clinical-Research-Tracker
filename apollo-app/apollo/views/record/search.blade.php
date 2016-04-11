<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */
use Apollo\Helpers\AssetHelper;
use Apollo\Helpers\URLHelper;

?>
@extends('layouts.extended')
@section('content')

    <ul class="nav nav-tabs" id="sort-tabs">
        <li role="presentation" class="sort-tab active" data-sort="1"><a href="#">All Records</a></li>
        <li role="presentation" class="sort-tab" data-sort="2"><a href="#">Recently added</a></li>
        <li role="presentation" class="sort-tab" data-sort="3"><a href="#">Recently updated</a></li>
    </ul>
    <div class="row">
        <div class="col-md-6 col-sx-12 top-buffer">
            <div class="input-group">
                        <span class="input-group-addon" id="basic-addon2"><span
                                    class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                <input type="text" class="form-control" id="records-search" placeholder="Search through the records..."
                       aria-label="Quick Search">
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-sx-12 top-buffer">
            <a href="{{ URLHelper::url('record/search') }}" class="btn btn-primary btn-block"><span
                        class="glyphicon glyphicon-search"
                        aria-hidden="true"></span>Advanced search</a>
        </div>
        <div class="col-md-2 col-sm-6 col-sx-12 top-buffer">
            <a href="#" id="add-record" class="btn btn-success btn-block"><span class="glyphicon glyphicon-plus"
                                                                                aria-hidden="true"></span>Add record</a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6 col-sx-12 top-buffer">
            <a href="{{URLHelper::url('get/peopleexcel')}}" target="_blank" id="download-records" class="btn btn-warning btn-block" > <span class="glyphicon glyphicon-download" aria-hidden="true"></span>Download</a>
        </div>
    </div>
    <div class="table-responsive top-buffer loader-ready">
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
    <script src="{{ AssetHelper::js('app/record/index') }}"></script>
@stop