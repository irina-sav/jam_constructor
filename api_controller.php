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
            if (empty($printData)) {
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
            }
            $data = mysqli_query(
                $bdConnect,
                "SELECT j.id, j.name, (c1.price + c2.price) as price FROM `jams` j inner join `components` c1 ON j.component_1 = c1.id inner join `components` c2 ON j.component_2 = c2.id WHERE j.id = $printData"
            );
            $printData = @mysqli_fetch_all($data, MYSQLI_ASSOC)[0];

            if (empty($printData)) {
                throw new Exception(
                    'ошибка при запросе. SQL error: ' . mysqli_error($bdConnect)
                );
            }

            exit(json_encode($printData, 256));
        } elseif (
            @$_POST['trashItems'] &&
            $_POST['name'] &&
            $_POST['phone'] &&
            $_POST['email']
        ) {
            $trashItems = json_decode($_POST['trashItems'], true);

            $data = mysqli_query(
                $bdConnect,
                "select `id` from `customer` where `phone`= '{$_POST['phone']}' or `email` = '{$_POST['email']}'"
            );
            $customerId = @mysqli_fetch_assoc($data)['id'];

            if (empty($customerId)) {
                $data = mysqli_query(
                    $bdConnect,
                    "insert into `customer` (`name`, `phone`, `email`) values ('{$_POST['name']}', '{$_POST['phone']}','{$_POST['email']}')"
                );
                $customerId = mysqli_insert_id($bdConnect);
                if (empty($customerId)) {
                    throw new Exception(
                        'ошибка при сохранении в БД. SQL error: ' .
                            mysqli_error($bdConnect)
                    );
                }
            }
            $jamsIds = implode(',', array_keys($trashItems));
            $orderAmount = mysqli_query(
                $bdConnect,
                "SELECT SUM(c1.price + c2.price) as amount FROM `jams` j inner join `components` c1 ON j.component_1 = c1.id inner join `components` c2 ON j.component_2 = c2.id WHERE j.id IN ({$jamsIds});"
            );
            $orderAmountValue = @mysqli_fetch_assoc($orderAmount)['amount'];

            $orderSave = mysqli_query(
                $bdConnect,
                "insert into `orders` (`customer`, `amount`, `comment`) values ('{$customerId}', '{$orderAmountValue}','{$_POST['comment']}')"
            );
            $orderSaveId = mysqli_insert_id($bdConnect);
            if (empty($orderSaveId)) {
                throw new Exception(
                    'ошибка при сохранении в БД. SQL error: ' .
                        mysqli_error($bdConnect)
                );
            }

            foreach ($trashItems as $trashItem) {
                $data = mysqli_query(
                    $bdConnect,
                    "select `id` from `boxes` where `jam` = '{$trashItem['jamId']}' and `quantity` = '{$trashItem['jamQty']}' and `order` = '{$orderSaveId}';"
                );
                $printData = @mysqli_fetch_assoc($data)['id'];

                if (empty($printData)) {
                    $data = mysqli_query(
                        $bdConnect,
                        "insert into `boxes` (`jam`, `quantity`, `order`) values ('{$trashItem['jamId']}', '{$trashItem['jamQty']}', '{$orderSaveId}' );"
                    );
                    $printData = mysqli_insert_id($bdConnect);
                    if (empty($printData)) {
                        throw new Exception(
                            'ошибка при сохранении в БД. SQL error: ' .
                                mysqli_error($bdConnect)
                        );
                    }
                }
            }

            $successOrder = "Ваш заказ №{$orderSaveId} успешно отправлен!";
            exit(json_encode($successOrder, 256));
        }

        throw new Exception('некорректное значение полей запроса');
    }
    throw new Exception('некорректный запрос');
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage();
}
