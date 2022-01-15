<?php
function requestToTelegramAPI(string $botMethod, array $options = []): string
{
    $options['chat_id'] = @$options['chat_id'] ?? ADMIN_CHAT_ID;
    $apiUrl =
        'https://api.telegram.org/bot' .
        BOT_TOKKEN .
        "/{$botMethod}?" .
        http_build_query($options);
    return file_get_contents($apiUrl);
}

function setWebHook(
    bool $webHookSet = true,
    string $path = 'bot_controller.php'
) {
    // if ($webHookSet) {
    //     $url = "{$_SERVER['HTTP_X_FORWARDED_PROTO']}://{$_SERVER['HTTP_HOST']}/{$path}";
    // } else {
    //     $url = '';
    // }

    $url = $webHookSet
        ? "{$_SERVER['HTTP_X_FORWARDED_PROTO']}://{$_SERVER['HTTP_HOST']}/{$path}"
        : '';

    $requestAPI = requestToTelegramAPI('setWebhook', ['url' => $url]);
    exit($requestAPI);
}

function addLog($info)
{
    $date = date('d.m.Y H:i:s');
    $oneLog = $date . ' ==> ' . print_r($info, true) . "\n";
    file_put_contents('logs/log.txt', $oneLog, FILE_APPEND);
}
