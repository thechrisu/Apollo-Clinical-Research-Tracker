<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.2
 */
use Apollo\Helpers\AssetHelper;

?>
@extends('layouts.extended')
@section('content')

    <div class="loader-container" id="loader">
        <div class="loader">
            <div class="line-1"></div>
            <div class="line-2"></div>
            <div class="line-3"></div>
            <div class="line-4"></div>
            <div class="line-5"></div>
        </div>
    </div>
    <div className="panel-body">
        <div className="row" id="recordGeneric">

        </div>
        <div className="row" id="recordDetails">

        </div>
    </div>

@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/record/single') }}"></script>
    <script src="http://eternicode.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
@stop