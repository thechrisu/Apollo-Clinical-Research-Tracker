<?php
/**
 * Layout to be used in pages visible to authorised users
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

use Apollo\Helpers\AssetHelper;

?>
@extends('layouts.basic')
@section('head')
    <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="{{ AssetHelper::css('stylesheet') }}">
@stop
@section('body')
    @include('templates.navbar')
    <div class="container top-buffer">
        <div class="panel panel-default">
            <div class="panel-heading">
                <ol class="breadcrumb" id="nav-breadcrumbs">
                    <li>Apollo</li>
                    <li><a href="record.php">Records</a></li>
                    <li class="active"><i>Record #531</i> Charlotte Warren-Gash</li>
                </ol>
            </div>
            <div class="panel-body">
                @yield('content')
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="https://use.typekit.net/xfk8ylv.js"></script>
    <script>try {
            Typekit.load({async: true});
        } catch (e) {
        }</script>
@stop