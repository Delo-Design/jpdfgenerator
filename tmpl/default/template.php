<?php

defined('_JEXEC') or die;

extract($displayData);

?>

<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>Карточки</title>
    </head>

    <style>
        @page {
            margin: 35px;
        }

        *,html,body
        {
            font-family: "Times New Roman";
        }

        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            padding: 5px 10px;
        }

        .desc {
            text-align: left;
            padding: 10px;
        }


    </style>

    <body>
        <p>Print_r variable</p>
        <p><?= print_r($displayData); ?></p>
    </body>
</html>
