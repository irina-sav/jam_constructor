<?php
$input = file_get_contents('php://input');
require_once 'config.php';
require_once 'bot_model.php';
require_once 'model.php';

// setWebHook();
// print_r(makeButton(ADMIN_BUTTONS));
// exit();

try {
    $inputArray = json_decode($input, true);
    addlog($inputArray);
    if (isset($inputArray['callback_query'])) {
        $options['chat_id'] = @$inputArray['callback_query']['message']['chat'][
            'id'
        ];
        $options['message_id'] = @$inputArray['callback_query']['message'][
            'message_id'
        ];
        if ($inputArray['callback_query']['data'] == 0) {
            $options['reply_markup'] = makeButton(COMPLETE_BUTTON);
        } elseif ($inputArray['callback_query']['data'] == 1) {
            $options['reply_markup'] = makeButton(PENDING_BUTTON);
        } else {
            throw new Exception('клик по неизвестной кнопке');
        }
        requestToTelegramAPI('editMessageReplyMarkup', $options);
        exit();
    } elseif (isset($inputArray['message'])) {
        $options['chat_id'] = @$inputArray['message']['chat']['id'];
        $message = mb_strtolower(@$inputArray['message']['text']);
        if ($message == 'button') {
            $options['text'] = 'Go Bot!';
            $options['reply_markup'] = makeButton(ADMIN_BUTTONS);
        } elseif ($message == 'test') {
            $dbConnect = sqlConnect();
            $options['text'] = makeOrderMessage(
                getOrderInfoById($dbConnect, 65)
            );
            $options['reply_markup'] = makeButton(PENDING_BUTTON);
        } else {
            $options['text'] =
                'Вы написали' . ' ' . @$inputArray['message']['text'];
        }
    } else {
        throw new Exception('неизвестный запрос к bot_controller');
    }

    $telegrammAnswer = requestToTelegramAPI('sendMessage', $options);
    addlog($telegrammAnswer);
} catch (Throwable $e) {
    addlog($e->getMessage());
    $options['text'] = 'error: ' . $e->getMessage();
    $options['chat_id'] = ADMIN_CHAT_ID;
    requestToTelegramAPI('sendMessage', $options);
}

// print_r(requestToTelegramAPI($messageMethod, ['text' => $sms]));
