<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.5
 */
use Apollo\Helpers\AssetHelper;
use Apollo\Helpers\URLHelper;

?>
@extends('layouts.extended')
@section('content')

    <div class="panel panel-default loader-ready" id="essential-panel">
        <div class="panel-heading">
            <p class="pull-right">Record actions:
                <a href="#" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-pencil"
                                                                 aria-hidden="true"></span> Edit</a>
                <a href="#" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"
                                                                aria-hidden="true"></span>
                    Delete</a>
            </p>
            <p>Essential information</p>
        </div>
        <div class="panel-body panel-no-padding" id="essential">

            <div class="row top-buffer">

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table no-border-top">
                            <tr>
                                <td>
                                    <small>Given Name</small>
                                </td>
                                <td><span class="undefined">None</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Middle Name</small>
                                </td>
                                <td><span class="undefined">None</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Last Name</small>
                                </td>
                                <td><span class="undefined">None</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table no-border-top">
                            <tr>
                                <td>
                                    <small>Email</small>
                                </td>
                                <td><span class="undefined">None</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Phone</small>
                                </td>
                                <td><span class="undefined">None</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table no-border-top">
                            <tr>
                                <td>
                                    <small>Address</small>
                                </td>
                                <td><span class="undefined">None</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="loader-ready" id="fields">

    </div>
@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/columns') }}"></script>
    <script src="{{ AssetHelper::js('app/record/single.view') }}"></script>
@stop