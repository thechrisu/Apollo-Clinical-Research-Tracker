<?php
/**
 * Layout to be used in pages visible to authorised users
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.5
 */

use Apollo\Apollo;
use Apollo\Helpers\AssetHelper;
$organisation = Apollo::getInstance()->getUser()->getOrganisationName();

?>
@extends('layouts.basic')
@section('head')

    <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ AssetHelper::css('stylesheet') }}" />
    <link rel="stylesheet" href="{{ AssetHelper::css('datepicker3.min') }}" />
    <link rel="stylesheet" href="{{ AssetHelper::css('bootstrap-tokenfield') }}" />
    <link rel="stylesheet" href="{{ AssetHelper::css('tokenfield-typeahead-custom') }}" />
    <link rel="icon" type="image/png" href="{{ AssetHelper::img('favicon.png') }}" />
    <title>{{ !empty($title) ? $title . ' | ' . APP_NAME : APP_NAME }}</title>
@stop
@section('body')
    @include('templates.navbar')
    <div class="container top-buffer">
        <div class="panel panel-default">
            @if(isset($breadcrumbs))
                <div class="panel-heading" id="breadcrumbHeader">
                    <ol class="breadcrumb" id="nav-breadcrumbs">
                        <li id="organisation">{{ $organisation }}</li>
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

    <footer>
        <div class="container">
            <p class="pull-left text-muted">&copy; 2016 UCL School of Life and Medical Sciences</p>
            <p class="pull-right text-muted">By Chris U, Desi K and Tim K</p>
        </div>
    </footer>

    <div class="modal fade" id="error-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">An error has occrurred</h4>
                </div>
                <div class="modal-body">
                    <p id="error-message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop
@section('scripts')
    <script src="{{ AssetHelper::js('app/scripts') }}"></script>
    <script src="{{ AssetHelper::js('app/ajax') }}"></script>
    <script src="{{ AssetHelper::js('plugins/jquery.simplePagination') }}"></script>
    <script src="{{ AssetHelper::js('plugins/bootstrap-tokenfield.min') }}"></script>
    <script src="{{ AssetHelper::js('plugins/affix') }}"></script>
    <script src="{{ AssetHelper::js('plugins/docs.min') }}"></script>
    <script src="{{ AssetHelper::js('plugins/scrollspy') }}"></script>
    <script src="{{ AssetHelper::js('plugins/typeahead.bundle.min') }}"></script>
    <script src="{{ AssetHelper::js('plugins/bootbox.min') }}"></script>
    <script src="http://eternicode.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
@stop