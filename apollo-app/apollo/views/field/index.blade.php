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

    <div class="table-responsive loader-ready">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Field name</th>
                <th>Type</th>
                <th>Defaults</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="table-body">
            </tbody>
        </table>
    </div>

@stop
@section('scripts')
    @parent
    <script src="{{ AssetHelper::js('app/inputs') }}"></script>
    <script src="{{ AssetHelper::js('app/field') }}"></script>
@stop