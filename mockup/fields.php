<?php

$title = 'Fields';
$page = 'fields';
include 'templates/header.php';
?>

    <div class="panel panel-default">

        <div class="panel-heading">
            <ol class="breadcrumb" id="nav-breadcrumbs">
                <li>Apollo</li>
                <li class="active">Fields</li>
            </ol>
        </div>

        <div class="panel-body">

            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#">Fields</a></li>
                <li role="presentation"><a href="#">Categories</a></li>
            </ul>

            <div class="table-responsive">
                <table class="table">

                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Constraint</th>
                        <th>Placeholder</th>
                        <th>Defaults</th>
                        <th>Parent</th>
                        <th>Relation</th>
                        <th>Value</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="field-table">
                        <tr id="field-template" style="display: none;">
                        <td>
                            <select class="form-control input-sm">
                                <option>Funding</option>
                                <option>Specialty</option>
                            </select>
                        </td>
                        <td>
                            <input class="form-control input-sm" type="text" placeholder="Name">
                        </td>
                        <td>
                            <select class="form-control input-sm">
                                <option>Integer</option>
                                <option>Date</option>
                                <option selected>String</option>
                                <option>Text</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control input-sm">
                                <option>None</option>
                                <option selected>Defaults only</option>
                                <option>Defaults & string</option>
                            </select>
                        </td>
                        <td>
                            <input class="form-control input-sm" type="text" placeholder="Placeholder">
                        </td>
                        <td>
                            <input class="form-control input-sm" type="text" placeholder="Defaults">
                        </td>
                        <td>
                            <select class="form-control input-sm">
                                <option>Some field</option>
                                <option selected>Another field</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control input-sm">
                                <option>Is equal to</option>
                                <option>Is greater than</option>
                                <option>Is smaller than</option>
                                <option selected>Not empty</option>
                            </select>
                        </td>
                        <td>
                            <input class="form-control input-sm" type="text" placeholder="Value">
                        </td>
                        <td><button class="btn-remove-field btn btn-default btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
                    </tr>
                        <tr>
                            <td>
                                <select class="form-control input-sm">
                                    <option>Funding</option>
                                    <option>Specialty</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm" type="text" placeholder="Name">
                            </td>
                            <td>
                                <select class="form-control input-sm">
                                    <option>Integer</option>
                                    <option>Date</option>
                                    <option selected>String</option>
                                    <option>Text</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm">
                                    <option>None</option>
                                    <option selected>Defaults only</option>
                                    <option>Defaults & string</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm" type="text" placeholder="Placeholder">
                            </td>
                            <td>
                                <input class="form-control input-sm" type="text" placeholder="Defaults">
                            </td>
                            <td>
                                <select class="form-control input-sm">
                                    <option>Some field</option>
                                    <option selected>Another field</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm">
                                    <option>Is equal to</option>
                                    <option>Is greater than</option>
                                    <option>Is smaller than</option>
                                    <option selected>Not empty</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm" type="text" placeholder="Value">
                            </td>
                            <td><button class="btn-remove-field btn btn-default btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
                        </tr>
                    </tbody>

                </table>
            </div>

            <div class="text-center top-buffer">

                <button class="btn btn-default btn-lg pull-left" id="add-a-field">Add a field</button>

                <button class="btn btn-default btn-lg pull-right">Update fields</button>

            </div>

        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('#add-a-field').click(function() {
                $('#field-template').clone().attr('id', '').attr('style', '').appendTo($('#field-table'));
            });
            $('#field-table').on('click','.btn-remove-field', function() {
                var that = $(this);
                that.parent().parent().remove();
            });
        });
    </script>

<?php

include 'templates/footer.php';
?>