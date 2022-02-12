<?php
require_once 'model.php';
try {
    if (!empty($_POST)) {
        $dbConnect = sqlConnect();

        if (is_numeric(@$_POST['componentId'])) {
            $printData = getComponentById($dbConnect, $_POST['componentId']);
            exit(json_encode($printData, 256));
        } elseif (@$_POST['jarName'] && $_POST['jarComponents']) {
            $printData = @getJamByName($dbConnect, $_POST['jarName'])['id'];

            if (empty($printData)) {
                $printData = addJam(
                    $dbConnect,
                    $_POST['jarName'],
                    $_POST['jarComponents']
                );
            }

            exit(
                json_encode(
                    addOneJamPriceAndParams($dbConnect, $printData),
                    256
                )
            );
        } elseif (
            @$_POST['trashItems'] &&
            $_POST['name'] &&
            $_POST['phone'] &&
            $_POST['email']
        ) {
            $customerId = checkCustomerByPhoneEmail(
                $dbConnect,
                $_POST['phone'],
                $_POST['email']
            );
            if (empty($customerId)) {
                $customerId = addCustomer($dbConnect, $_POST);
            }

            $orderSaveId = makeOrder(
                $dbConnect,
                $customerId,
                $_POST['comment'],
                json_decode($_POST['trashItems'], true)
            );

            $jamsIds = implode(',', array_keys($trashItems));

            $orderAmountValue = getOrderAmountById($dbConnect, $orderSaveId);

            addAmountToOrder($dbConnect, $orderAmountValue, $orderSaveId);

            $successOrder = "Ваш заказ №{$orderSaveId} успешно отправлен!";
            exit(json_encode($successOrder, 256));
        }

        throw new Exception('некорректное значение полей запроса');
    }
    throw new Exception('некорректный запрос');
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage();
}
