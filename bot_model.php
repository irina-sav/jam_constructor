<?php
function requestToTelegramAPI(string $botMethod, array $options = []): string
{
    $options['chat_id'] = ADMIN_CHAT_ID;
    $apiUrl =
        'https://api.telegram.org/bot' .
        BOT_TOKKEN .
        "/{$botMethod}?" .
        http_build_query($options);
    return file_get_contents($apiUrl);
}
