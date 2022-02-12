<?php
$input = file_get_contents('php://input');
require_once 'config.php';
require_once 'bot_model.php';
// setWebHook();
// print_r(makeButton(ADMIN_BUTTONS));
// exit();

try {
    $inputArray = json_decode($input, true);
    addlog($inputArray);
    $chatId = @$inputArray['message']['chat']['id'];
    $message = mb_strtolower(@$inputArray['message']['text']);
    $options = [
        'chat_id' => $chatId,
        'text' => '',
        'reply_markup' => '',
    ];

    if ($message == 'button') {
        $options['text'] = 'Go Bot!';
        $options['reply_markup'] = makeButton(ADMIN_BUTTONS);
    } elseif ($message == 'test') {
        $options['text'] = makeOrderMessage(TEST_ORDER_ARRAY);
    } else {
        $options['text'] =
            'Вы написали' . ' ' . @$inputArray['message']['text'];
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
