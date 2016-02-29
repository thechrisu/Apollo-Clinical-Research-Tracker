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
    <title>{{ !empty($title) ? $title . ' | ' . APP_NAME : APP_NAME }}</title>
@stop
@section('body')
    @include('templates.navbar')
    <div class="container top-buffer">
        <div class="panel panel-default">
            @if(isset($breadcrumbs))
                <div class="panel-heading">
                    <ol class="breadcrumb" id="nav-breadcrumbs">
                        <li>{{ \Apollo\Apollo::getInstance()->getUser()->getOrganisationName() }}</li>
                        @foreach($breadcrumbs as $breadcrumb)
                            <li{!! $breadcrumb[2] ? ' class="active"' : '' !!}>
                                @if($breadcrumb[1] != null)
                                    <a href="{{ $breadcrumb[1] }}">{!! $breadcrumb[0] !!}</a>
                                @else
                                    {!! $breadcrumb[0] !!}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
            <div id="main-panel" class="panel-body">
                @yield('content')
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="https://use.typekit.net/xfk8ylv.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-dom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js"></script>
    <script>try {
            Typekit.load({async: true});
        } catch (e) {
        }</script>
@stop