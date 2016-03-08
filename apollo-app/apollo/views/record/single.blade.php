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
            <span class="pull-right">Record actions:<!--
             --><a href="#" id="record-add" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add a record</a><!--
             --><a href="#" id="record-edit" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Edit</a><!--
             --><a href="#" id="record-hide" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Hide</a>
            </span>
            <span>
                <span>Current record:</span><!--
             --><div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true">
                            Dropdown
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                </div>
            </span>
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
                                <td><span class="undefined">Loading...</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Middle Name</small>
                                </td>
                                <td><span class="undefined">Loading...</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Last Name</small>
                                </td>
                                <td><span class="undefined">Loading...</span></td>
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
                                <td><span class="undefined">Loading...</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Phone</small>
                                </td>
                                <td><span class="undefined">Loading...</span></td>
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
                                <td><span class="undefined">Loading...</span></td>
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