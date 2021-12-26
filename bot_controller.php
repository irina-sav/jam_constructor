<?php
require_once 'config.php';
require_once 'bot_model.php';
// $botMethod = 'getUpdates';
$messageMethod = 'sendMessage';
$sms = 'Hello, Irina';

print_r(requestToTelegramAPI($messageMethod, ['text' => $sms]));
