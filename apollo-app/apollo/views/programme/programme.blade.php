<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

use Apollo\Helpers\AssetHelper;
use Apollo\Helpers\URLHelper;

?>
@extends('layouts.extended')
@section('content')
    <div id="programme-wrapper">
        <div class="col-lg-4">
            <div class="input-group-sm">
                <input type="text" class="form-control input-small" id="programmes-search" placeholder="Search programmes...">
            </div>

        <div id="programmeTable" class="table-responsive">
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
            <div class="loader-container" id="menuLoader">
                <div class="loader">
                    <div class="line-1"></div>
                    <div class="line-2"></div>
                    <div class="line-3"></div>
                    <div class="line-4"></div>
                    <div class="line-5"></div>
                </div>
            </div>
            <tr>
                <td>Programme 1</td>
            </tr>
            <tr>
                <td>Programme 2</td>
            </tr>
            </tbody>
        </table>
        </div>

    </div>
    <div class="col-lg-8">
        <div class="col-sm-4 btn-group-sm">
            <a href="#" id="add-record" class="btn btn-default btn-block">Add a Programme</a>
        </div>
        <div id="programmeContent" class="top-buffer">
            <div class="loader-container" id="contentLoader">
                <div class="loader">
                    <div class="line-1"></div>
                    <div class="line-2"></div>
                    <div class="line-3"></div>
                    <div class="line-4"></div>
                    <div class="line-5"></div>
                </div>
            </div>
            Programme 1
        </div>
    </div>


        <nav class="text-center">
            <ul class="pagination" id="pagination">
            </ul>
        </nav>


    </div>

@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/programme/index') }}"></script>
@stop