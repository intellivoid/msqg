<?php

    $Source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($Source . 'msqg' . DIRECTORY_SEPARATOR . 'msqg.php');

    $MySQLi = new mysqli('127.0.0.1', 'admin', 'admin', 'intellivoid');

    $Query = \msqg\QueryBuilder::select(
        null, 'users', ['username', 'email'], 'verified', 1,
        'id', \msqg\Abstracts\SortBy::ascending,
        100, 12
    );

    print($Query . PHP_EOL);