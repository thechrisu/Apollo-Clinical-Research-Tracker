<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.2
 */

use Apollo\Helpers\AssetHelper;
use Apollo\Helpers\URLHelper;

?>
@extends('layouts.extended')
@section('content')
    <div class="row">
        <div class="input-group-sm col-lg-4">
            <input type="text" class="form-control input-small" id="programmes-search"
                   placeholder="Search programmes...">
        </div>
        <div class="col-sm-2 btn-group-sm">
            <a href="#" id="add-record" class="btn btn-default btn-block">Add a Programme</a>
        </div>

    </div>

    <div id="programme-wrapper" class="row">
        <div class="col-lg-4 divider-vertical table-responsive menu-loader-ready" id="programmeTable">
            <table class="table table-hover small-table">
                <thead>
                <tr>
                    <th>
                        Programme name
                    </th>
                    <th>
                        Start Date
                    </th>
                    <th>
                        End date
                    </th>
                </tr>
                </thead>
                <tbody id="table-body">
                <tr>
                    <td>Programme 1</td>
                </tr>
                <tr>
                    <td>Programme 2</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-lg-8 content-loader-ready" id="programmeContent">
            <div class="programme-title top-buffer">
                Programme 1
            </div>
            <div class="target-group top-buffer">
                Target group (dropdown list and comment field
            </div>
            <div class="funding top-buffer">
                Programme funding
            </div>
            <div class="row top-buffer">
                <div class="col-lg-12">
                    <input type="text" class="form-control input-sm" id="person-input"/>
                </div>
            </div>
            <div class="row top-buffer">
                <div class="col-lg-6">
                    Start date
                </div>
                <div class="col-lg-6">
                    End date
                </div>
        </div>


        <nav class="text-center">
            <ul class="pagination" id="pagination">
            </ul>
        </nav>


    </div>


    </div>

@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/programme/programme') }}"></script>
@stop