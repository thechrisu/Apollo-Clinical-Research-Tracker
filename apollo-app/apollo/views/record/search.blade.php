<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.2
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
    <div class="panel panel-default top-buffer">
        <div class="panel-heading">Filters</div>
        <div class="panel-body">
            <div class="table-responsive loader-ready filter">
                <table class="table table-striped table-hover table-no-margin">
                    <thead>
                    <tr>
                        <th>Field</th>
                        <th>Relation</th>
                        <th>Value</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="filter-table">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="table-responsive top-buffer loader-ready record">
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
    @include('templates.pagination')
@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/inputs') }}"></script>
    <script src="{{ AssetHelper::js('app/columns') }}"></script>
    <script src="{{ AssetHelper::js('app/apollopagination') }}"></script>
    <script src="{{ AssetHelper::js('app/record/search') }}"></script>
@stop