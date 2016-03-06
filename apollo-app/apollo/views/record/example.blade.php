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

    <div class="panel panel-default">
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
        <div class="panel-body">

            <div class="row top-buffer">

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td>
                                    <small>Given Name</small>
                                </td>
                                <td><strong>James</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Middle Name</small>
                                </td>
                                <td><b>Houka</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Last Name</small>
                                </td>
                                <td><strong>Bond</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td>
                                    <small>Email</small>
                                </td>
                                <td><strong>james.bond@mi6.gov.uk</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <small>Phone</small>
                                </td>
                                <td><b>+44 0000 007</b></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td rowspan="2">
                                    <small>Address</small>
                                </td>
                                <td><strong>85 Albert Embankment</strong></td>
                            </tr>
                            <tr>
                                <td><strong>London, SE1 7TP</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row top-buffer">

        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>
                            <small>Supervisor</small>
                        </td>
                        <td><strong>Nelson Mandela</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <small>Car</small>
                        </td>
                        <td><b>Aston Martin DB5</b></td>
                    </tr>
                    <tr>
                        <td>
                            <small>Funding Source</small>
                        </td>
                        <td><strong>Top Secret</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <small>References</small>
                        </td>
                        <td><b>Mister Bond is one of our nicest employees. In fact, he even developed new applications
                                in conjunction with Q. He is always punctual.</b></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>
                            <small>Car</small>
                        </td>
                        <td><b>Aston Martin DB5</b></td>
                    </tr>
                    <tr>
                        <td>
                            <small>Supervisor</small>
                        </td>
                        <td><strong>Nelson Mandela</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <small>Speciality</small>
                        </td>
                        <td><b>Making cheesy comments</b></td>
                    </tr>
                    <tr>
                        <td>
                            <small>Funding Source</small>
                        </td>
                        <td><strong>Top Secret</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>
                            <small>Funding Source</small>
                        </td>
                        <td><strong>Top Secret</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <small>Car</small>
                        </td>
                        <td><b>Aston Martin DB5</b></td>
                    </tr>
                    <tr>
                        <td>
                            <small>Speciality</small>
                        </td>
                        <td><b>Making cheesy comments</b></td>
                    </tr>
                    <tr>
                        <td>
                            <small>Supervisor</small>
                        </td>
                        <td><strong>Nelson Mandela</strong></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/record/single.view') }}"></script>
@stop