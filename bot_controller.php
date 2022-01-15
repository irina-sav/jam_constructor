<?php
$input = file_get_contents('php://input');
require_once 'config.php';
require_once 'bot_model.php';
// setWebHook();

$inputArray = json_decode($input, true);
addlog($inputArray);
if ($chatId = @$inputArray['message']['chat']['id']) {
    requestToTelegramAPI('sendMessage', [
        'chat_id' => $chatId,
        'text' => 'Вы написали' . ' ' . @$inputArray['message']['text'],
    ]);
}
// $botMethod = 'getUpdates';
$messageMethod = 'sendMessage';
$sms = 'Hello, Irina';

// print_r(requestToTelegramAPI($messageMethod, ['text' => $sms]));
