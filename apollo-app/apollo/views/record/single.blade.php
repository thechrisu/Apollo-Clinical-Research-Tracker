<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */
use Apollo\Helpers\AssetHelper;
?>
@extends('layouts.extended')
@section('content')
    <div class="alert alert-success alert-dismissable">

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>

        &nbsp;&nbsp;&nbsp;&nbsp;You have successfully updated the record #531 belonging to Charlotte
        Warren-Gash.

    </div>

    <div id="recordDetails">

    </div>

@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/record/single') }}"></script>
@stop