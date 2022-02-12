<?php
$input = file_get_contents('php://input');
require_once 'config.php';
require_once 'bot_model.php';
// setWebHook();
try {
    $inputArray = json_decode($input, true);
    addlog($inputArray);
    $chatId = @$inputArray['message']['chat']['id'];
    $message = mb_strtolower(@$inputArray['message']['text']);
    $options = [
        'chat_id' => $chatId,
        'text' => '',
    ];

    if (strContainsByRegExp($message, GREETING_PHRASES)) {
        $options['text'] =
            'Привет' . ' ' . @$inputArray['message']['chat']['username'];
    } elseif (strContainsByRegExp($message, BYE_PHRASES)) {
        $options['text'] =
            'Пока' . ' ' . @$inputArray['message']['chat']['username'];
    } elseif ($message == '/start') {
        $options['text'] = 'Поздоровайтесь  с ботом!';
    } else {
        $options['text'] =
            'Вы написали' . ' ' . @$inputArray['message']['text'];
    }

    requestToTelegramAPI('sendMessage', $options);
} catch (Throwable $e) {
    addlog($e->getMessage());
    $options['text'] = 'error: ' . $e->getMessage();
    $options['chat_id'] = ADMIN_CHAT_ID;
    requestToTelegramAPI('sendMessage', $options);
}

// $botMethod = 'getUpdates';
$messageMethod = 'sendMessage';
$sms = 'Hello, Irina';

// print_r(requestToTelegramAPI($messageMethod, ['text' => $sms]));
