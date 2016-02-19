<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */
?>
@extends('layouts.extended')
@section('content')
    <div class="jumbotron error-page">

        <h1>{{ $status_code }}</h1>

        <p>{{ $message }}</p>

    </div>
@stop