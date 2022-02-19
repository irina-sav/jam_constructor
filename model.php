<?php
function sqlConnect()
{
    $dbConnect = mysqli_connect(
        '31.31.196.95',
        'u1433184_jam',
        'hG0uI8rU9ksS3f',
        'u1433184_jambase'
    );
    mysqli_query($dbConnect, 'SET NAMES utf8');

    if (!$dbConnect) {
        throw new Exception(
            'Ошибка подключения (' .
                mysqli_connect_errno() .
                ') ' .
                mysqli_connect_error()
        );
    }

    return $dbConnect;
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

function getComponentById($dbConnect, int $id)
{
    $data = mysqli_query(
        $dbConnect,
        "select * from `components` where `id` = $id"
    );
    $printData = mysqli_fetch_assoc($data);
    if (empty($printData)) {
        throw new Exception('не найдено в БД');
    }
    return $printData;
}

function getJamByName($dbConnect, $name)
{
    $data = mysqli_query(
        $dbConnect,
        "select * from `jams` where `name`= '{$name}'"
    );
    return @mysqli_fetch_assoc($data);
}

function addJam($dbConnect, $name, $components): int
{
    $jarData = array_filter($_POST['jarComponents'], function ($e) {
        return !empty($e);
    });
    array_multisort($jarData, SORT_ASC);
    $data = mysqli_query(
        $dbConnect,
        "insert into `jams` (`name`, `component_1`, `component_2`) values ('{$name}', {$jarData[0]['id']}, {$jarData[1]['id']})"
    );
    $jamId = mysqli_insert_id($dbConnect);
    if (empty($jamId)) {
        throw new Exception(
            'ошибка при сохранении в БД. SQL error: ' . mysqli_error($dbConnect)
        );
    }
    return $jamId;
}

function addOneJamPriceAndParams($dbConnect, $id): array
{
    $data = mysqli_query(
        $dbConnect,
        "SELECT j.*, (c1.price + c2.price) as price FROM `jams` j inner join `components` c1 ON j.component_1 = c1.id inner join `components` c2 ON j.component_2 = c2.id WHERE j.id = $id"
    );
    $jamData = @mysqli_fetch_all($data, MYSQLI_ASSOC)[0];

    if (empty($jamData)) {
        throw new Exception(
            'ошибка при запросе. SQL error: ' . mysqli_error($dbConnect)
        );
    }
    return $jamData;
}

function checkCustomerByPhoneEmail($dbConnect, $phone, $email): ?int
{
    $data = mysqli_query(
        $dbConnect,
        "select `id` from `customer` where `phone`= '{$phone}' or `email` = '{$email}'"
    );
    return @mysqli_fetch_assoc($data)['id'];
}

function addCustomer($dbConnect, $userData): int
{
    $data = mysqli_query(
        $dbConnect,
        "insert into `customer` (`name`, `phone`, `email`) values ('{$userData['name']}', '{$userData['phone']}','{$userData['email']}')"
    );
    $customerId = mysqli_insert_id($dbConnect);

    if (empty($customerId)) {
        throw new Exception(
            'ошибка при сохранении в БД. SQL error: ' . mysqli_error($dbConnect)
        );
    }
    return $customerId;
}
function getBoxByParams($dbConnect, $jamId, $jamQty, $orderId): ?array
{
    $data = mysqli_query(
        $dbConnect,
        "select * from `boxes` where `jam` = '{$jamId}' and `quantity` = '{$jamQty}' and `order` = '{$orderId}';"
    );
    return @mysqli_fetch_assoc($data);
}

function makeOrder($dbConnect, $customerId, $comment, $trashItems): int
{
    $orderSave = mysqli_query(
        $dbConnect,
        "insert into `orders` (`customer`, `comment`) values ('{$customerId}','{$comment}')"
    );
    $orderSaveId = mysqli_insert_id($dbConnect);
    if (empty($orderSaveId)) {
        throw new Exception(
            'ошибка при сохранении в БД. SQL error: ' . mysqli_error($dbConnect)
        );
    }

    foreach ($trashItems as $trashItem) {
        $printData = @getBoxByParams(
            $dbConnect,
            $trashItem['jamId'],
            $trashItem['jamQty'],
            $orderSaveId
        )['id'];
        if (empty($printData)) {
            $data = mysqli_query(
                $dbConnect,
                "insert into `boxes` (`jam`, `quantity`, `order`) values ('{$trashItem['jamId']}', '{$trashItem['jamQty']}', '{$orderSaveId}' );"
            );
            $printData = mysqli_insert_id($dbConnect);
            if (empty($printData)) {
                throw new Exception(
                    'ошибка при сохранении в БД. SQL error: ' .
                        mysqli_error($dbConnect)
                );
            }
        }
    }
    return $orderSaveId;
}

function getOrderAmountById($dbConnect, $orderId): int
{
    $orderAmount = mysqli_query(
        $dbConnect,
        "SELECT SUM(c.price * b.quantity) AS amount FROM `orders` o INNER JOIN `boxes` b ON b.order = o.id INNER JOIN `jams` j ON j.id = b.jam INNER JOIN `components` c ON c.id IN(j.component_1, j.component_2) WHERE o.id = {$orderId}"
    );
    return @mysqli_fetch_assoc($orderAmount)['amount'];
}

function addAmountToOrder($dbConnect, $amount, $orderId): int
{
    $updateOrder = mysqli_query(
        $dbConnect,
        "UPDATE `orders` SET `amount` = {$amount} WHERE `id` = {$orderId}"
    );
    $updateOrderId = mysqli_affected_rows($dbConnect);
    if (empty($updateOrderId)) {
        throw new Exception(
            'сумма заказа не сохранена. SQL error: ' . mysqli_error($dbConnect)
        );
    }
    return $updateOrderId;
}

function getOrderInfoById($dbConnect, int $id): array
{
    $orderInfo = mysqli_query(
        $dbConnect,
        "SELECT o.`id` AS 'number',c.`name` as 'user', c.`phone`, GROUP_CONCAT(b.`id`) as 'boxIds', o.`amount`
    FROM `orders` o  
    INNER JOIN `customer` c ON c.`id` = o.`customer` 
    INNER JOIN `boxes` b ON b.`order` = o.`id`
    WHERE o.`id` = {$id}"
    );
    $orderInfoArray = mysqli_fetch_assoc($orderInfo);
    $boxData = mysqli_query(
        $dbConnect,
        "SELECT j.`name`, b.`quantity`, GROUP_CONCAT(c.`name`) AS 'components' FROM `boxes` b 
            INNER JOIN `jams` j ON b.`jam` = j.`id`
            INNER JOIN `components` c ON j.component_1 = c.`id` OR j.`component_2` = c.`id`
            WHERE b.`id` IN ({$orderInfoArray['boxIds']})
            GROUP BY b.`id`"
    );
    $boxDataArray = mysqli_fetch_all($boxData, MYSQLI_ASSOC);
    $items = '';

    foreach ($boxDataArray as $boxDataItem) {
        $items .= " - {$boxDataItem['name']} ({$boxDataItem['components']}) {$boxDataItem['quantity']} шт. \n";
    }
    return [
        'number' => $orderInfoArray['number'],
        'user' => $orderInfoArray['user'],
        'phone' => $orderInfoArray['phone'],
        'amount' => $orderInfoArray['amount'],
        'items' => $items,
    ];
}

print_r(getOrderInfoById(sqlConnect(), 65));
