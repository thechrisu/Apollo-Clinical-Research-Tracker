<?php
/**
 * Sign in page which all unauthorised users will see.
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */
?>

@extends('layouts.default')
@section('head')
    <title>Sign In | Apollo</title>
    <style>
        body {
            position: relative;
        }

        .top-buffer {
            margin-top: 20px;
        }
        body {
            transform: translateX(-50%) translateY(-50%);
            position: absolute;
            left: 50%;
            top: 50%;
        }
    </style>
@stop
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Please sign in:</div>
                    <div class="panel-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon2"><span
                                                class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                                        <input type="email" class="form-control" name="email" placeholder="john.smith@example.co.uk"
                                               aria-label="Email">
                                    </div>
                                </div>
                            </div>

                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <div class="input-group input-group-md">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"
                                                                          aria-hidden="true"></span></span>
                                        <input type="password" class="form-control" name="password" placeholder="*************"
                                               aria-label="Password">
                                    </div>
                                </div>
                            </div>

                            <div class="row top-buffer">

                                <div class="col-md-8"><a href="#" class="btn btn-default">Forgot your password?</a>
                                </div>

                                <div class="col-md-4">
                                    <!--<button type="submit" class="btn btn-primary btn-block">Sign in</button>-->
                                    <input type="submit" value="Sign In" class="btn btn-primary btn-block" />
                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop