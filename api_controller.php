<?php
try {
    if (!empty($_POST)) {
        $bdConnect = mysqli_connect(
            '31.31.196.95',
            'u1433184_jam',
            'hG0uI8rU9ksS3f',
            'u1433184_jambase'
        );
        mysqli_query($bdConnect, 'SET NAMES utf8');

        if (!$bdConnect) {
            throw new Exception(
                'Ошибка подключения (' .
                    mysqli_connect_errno() .
                    ') ' .
                    mysqli_connect_error()
            );
        }

        if (is_numeric(@$_POST['componentId'])) {
            $data = mysqli_query(
                $bdConnect,
                "select * from `components` where `id` = {$_POST['componentId']}"
            );
            $printData = mysqli_fetch_assoc($data);
            if (empty($printData)) {
                throw new Exception('не найдено в БД');
            }

            exit(json_encode($printData, 256));
        } elseif (@$_POST['jarName'] && $_POST['jarComponents']) {
            $jarData = array_filter($_POST['jarComponents'], function ($e) {
                return !empty($e);
            });
            array_multisort($jarData, SORT_ASC);

            $data = mysqli_query(
                $bdConnect,
                "select `id` from `jams` where `name`= '{$_POST['jarName']}'"
            );
            $printData = @mysqli_fetch_assoc($data)['id'];
            if (!empty($printData)) {
                exit(json_encode($printData, 256));
            }

            $data = mysqli_query(
                $bdConnect,
                "insert into `jams` (`name`, `component_1`, `component_2`) values ('{$_POST['jarName']}', {$jarData[0]['id']}, {$jarData[1]['id']})"
            );
            $printData = mysqli_insert_id($bdConnect);
            if (empty($printData)) {
                throw new Exception(
                    'ошибка при сохранении в БД. SQL error: ' .
                        mysqli_error($bdConnect)
                );
            }

            exit(json_encode($printData, 256));
        }
        throw new Exception('некорректное значение полей запроса');
    }
    throw new Exception('некорректный запрос');
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage();
}
