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
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="input-group-sm col-lg-4">
                    <input type="text" class="form-control input-small" id="activities-search"
                           placeholder="Search activities...">
                </div>
                <div class="col-sm-2 btn-group-sm">
                    <a href="#" id="add-activity" class="btn btn-default btn-block">Add an activity</a>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div id="activity-wrapper" class="row">
                <div class="col-lg-4 divider-vertical table-responsive menu-loader-ready" id="activityTable">
                    <table class="table table-hover small-table no-border-top">
                        <tbody id="table-body">
                        </tbody>
                    </table>

                    <nav class="text-center">
                        <ul class="pagination" id="pagination">
                        </ul>
                    </nav>

                </div>
                <div class="col-lg-8 content-loader-ready" id="activityContent">
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-sx-12 top-buffer">
                            <input class="form-control input-medium" id="activity-title"/>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-sx-12 top-buffer top-buffer">
                            <div class="btn-group btn-block">
                                <button class="btn btn-block btn-primary dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        id="target-button">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" id="target-dropdown"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="row top-buffer">
                        <div class="col-lg-6 col-md-6 col-sx-12 col-sm-12">
                            <table class="table table-hover small-table no-border-top">
                                <tbody id="existingPeople">
                                </tbody>
                            </table>
                            <form  onSubmit="return false;">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control input-sm" id="person-input"
                                           placeholder="Add more people..." />
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sx-12 col-sm-12">
                            <div class="form-group">
                                <textarea class="form-control" id="target-comment" rows="5" placeholder="Enter activity description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row top-buffer">
                        <div class="col-lg-6" id="start-date">
                            <small>Start date</small>
                        </div>
                        <div class="col-lg-6" id="end-date">
                            <small>End date</small>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/activity/activity') }}"></script>
@stop