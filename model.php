<?php
function sqlConnect()
{
    $bdConnect = mysqli_connect(
        '31.31.196.95',
        'u1433184_jam',
        'hG0uI8rU9ksS3f',
        'u1433184_jambase'
    );
    mysqli_query($bdConnect, 'SET NAMES utf8');
    // if (!$bdConnect) {
    //     throw new Exception('<br>' . 'Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    // }
    return $bdConnect;
}

function getComponents()
{
    $data = mysqli_query(sqlConnect(), 'select * from `components`');
    return mysqli_fetch_all($data, MYSQLI_ASSOC);
}

function getReadyJams()
{
    $data = mysqli_query(
        sqlConnect(),

        'SELECT
        j.id,
        j.name,
        (c1.price + c2.price) AS price
    FROM
        `jams` j
    INNER JOIN `components` c1 ON
        j.component_1 = c1.id
    INNER JOIN `components` c2 ON
        j.component_2 = c2.id
    WHERE
        j.availible = 1'
    );
    return mysqli_fetch_all($data, MYSQLI_ASSOC);
}
