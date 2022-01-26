<?php

use Discord\Parts\Channel\Message;

function run(array $args, Message $message)
{
    $content = implode(' ', $args);

    try {
        $message->channel->sendMessage($content);
    } catch (Exception $e) {
        echo $e;
    }
}
