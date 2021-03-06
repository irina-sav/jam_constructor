<?php
require_once 'config.php';
require_once 'model.php';
require_once 'bot_model.php';
require_once 'Logger.php';


try {
    $simpleLog = new Logger();
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

            $orderAmountValue = getOrderAmountById($dbConnect, $orderSaveId);

            addAmountToOrder($dbConnect, $orderAmountValue, $orderSaveId);

            $options['chat_id'] = ADMIN_CHAT_ID;
            $options['text'] = makeOrderMessage(
                getOrderInfoById($dbConnect, $orderSaveId)
            );
            $options['reply_markup'] = makeButton(PENDING_BUTTON);
            $telegrammAnswer = requestToTelegramAPI('sendMessage', $options);

            
            $simpleLog->addLog($telegrammAnswer);


            // addlog($telegrammAnswer);

            setOrderMessageId(
                $dbConnect,
                $orderSaveId,
                $telegrammAnswer['result']['message_id']
            );

            $successOrder = "?????? ?????????? ???{$orderSaveId} ?????????????? ??????????????????!";

            exit(json_encode($successOrder, 256));
        }

        throw new Exception('???????????????????????? ???????????????? ?????????? ??????????????');
    }
    throw new Exception('???????????????????????? ????????????');
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage();
}
