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

    <div id="add-modal" style="display: none;">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-md-3 control-label" for="name">Name</label>
                <div class="col-md-8">
                    <input id="add-name" type="text"
                           class="form-control input-md">
                    <span class="help-block">The name for the new record</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="name">Time period:</label>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group date" data-provide="datepicker">
                                <input id="add-start-date" type="text" placeholder="Start date"
                                       class="form-control"><span
                                        class="input-group-addon"><i
                                            class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group date" data-provide="datepicker">
                                <input id="add-end-date" type="text" placeholder="End date" class="form-control"><span
                                        class="input-group-addon"><i
                                            class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                    <span class="help-block">Time period covered by this record.</span>
                </div>
            </div>
        </form>
    </div>

    <div class="panel panel-default loader-ready" id="essential-panel">
        <div class="panel-heading">
            <span class="pull-right">Record actions:<!--
             --><a href="#" id="record-add" class="btn btn-sm btn-success disabled"><span
                            class="glyphicon glyphicon-plus"
                            aria-hidden="true"></span>Add a record</a><!--
             --><a href="#" id="record-duplicate" class="btn btn-sm btn-primary disabled"><span
                            class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>Duplicate record</a><!--
             --><a href="#" id="record-edit" class="btn btn-sm btn-warning disabled"><span
                            class="glyphicon glyphicon-pencil"
                            aria-hidden="true"></span>Edit</a><!--
             --><a href="#" id="record-hide" class="btn btn-sm btn-danger disabled"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Hide record</a><!--
             --><a href="#" id="person-hide" class="btn btn-sm btn-danger disabled"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Hide person</a>
            </span>
            <span>
                <span>Current record:</span><!--
             --><div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle disabled" id="current-record" type="button"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true">
                        Current record
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="other-records" aria-labelledby="current-record">
                        <li class="dropdown-header">View other records:</li>
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
                            <tr>
                                <td>
                                    <small>Email</small>
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
                                    <small>Phone</small>
                                </td>
                                <td><span class="undefined">Loading...</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Record name</small>
                                </td>
                                <td><span class="undefined">Loading...</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Record start date</small>
                                </td>
                                <td><span class="undefined">Loading...</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Record end date</small>
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

    <div class="panel panel-default loader-ready" id="additional-panel">
        <div class="panel-heading">Additional info</div>
        <div class="panel-body" id="additional">

            <div class="row top-buffer">

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Awards</div>
                        <div class="panel-body" id="awards">
                            <div class="apollo-data-text-multiple"><span class="undefined">Loading...</span></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Publications</div>
                        <div class="panel-body" id="publications">
                            <div class="apollo-data-text-multiple"><span class="undefined">Loading...</span></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Activities</div>
                        <div class="panel-body" id="activities">
                            <div class="apollo-data-text-multiple"><span class="undefined">Loading...</span></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/columns') }}"></script>
    <script src="{{ AssetHelper::js('app/record/single.view') }}"></script>
@stop