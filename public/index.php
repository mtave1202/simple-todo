<?php
require_once __DIR__ . '/../app/_include.php';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Simple ToDo</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="wrapper">
        <div class="pd10 bg-white">
            <table id="todo-list" class="table">
                <thead>
                    <tr class="tr">
                        <th colspan="2" class="text-left">
                            <h1>Simple ToDo</h1>
                        </th>
                        <th class="w-button">
                            <span id="clock"></span>
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</body>

<template id="template-row">
    <tr class="todo" data-id="">
        <td class="w-toggle">
            <label class="fa-checkbox">
                <input class="fa-checkbox-input status" type="checkbox" name="status" />
                <span class="fa-checkbox-label"></span>
            </label>
        </td>
        <td>
            <input type="text" class="title input">
        </td>
        <td>
            <button type="button" class="btn btn-delete"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
</template>
<script src="/js/app.js"></script>

</html>