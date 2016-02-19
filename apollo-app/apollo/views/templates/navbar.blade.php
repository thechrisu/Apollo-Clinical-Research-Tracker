<?php
/**
 * Navigation bar template to be included on every page
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

use Apollo\Apollo;
use Apollo\Helpers\URLHelper;

$controller = Apollo::getInstance()->getRequest()->getController();
$menu_points = [
        ['Record', 'record', 'Records'],
        ['Programme', 'programme', 'Programmes'],
        ['Field', 'field', 'Fields'],
        ['Help', 'help', 'Help'],
]
?>
<nav id="navbar" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ BASE_URL }}">{{ APP_NAME }}</a>
        </div>
        <div id="navbar-collapse" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">
                <li class="navbar-text">Signed in as {{ Apollo::getInstance()->getUser()->getName() }}</li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @foreach($menu_points as $menu_point)
                    <li{!! $controller == $menu_point[0] ? ' class="active"' : '' !!}><a
                                href="{{ URLHelper::url($menu_point[1]) }}">{{ $menu_point[2] }}</a></li>
                @endforeach
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">User Settings <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @if (Apollo::getInstance()->getUser()->isAdmin())
                            <li class="dropdown-header">Admin Section</li>
                            <li><a href="{{ URLHelper::url('user/manage') }}">Manage Users</a></li>
                            <li role="separator" class="divider"></li>
                        @endif
                        <li><a href="{{ URLHelper::url('user/settings') }}">Settings</a></li>
                        <li><a href="{{ URLHelper::url('user/sign-out') }}">Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
