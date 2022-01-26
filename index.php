<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\Parts\Channel\Message;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$discord = new Discord([
    'token' => $_ENV['TOKEN'],
]);

$discord->on('ready', function (Discord $discord) {
    echo "{$discord->username} is ready!";

    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        if (!$message->guild_id) return;

        $prefix = $_ENV['PREFIX'];
        if (!str_starts_with($message->content, $prefix)) return;

        $args = substr($message->content, strlen($prefix));
        $args = trim($args);
        $args = explode(' ', $args);

        $command = count($args) > 0 ? strtolower(array_shift($args)) : '';

        if (file_exists(__DIR__ . '/commands/' . $command . '.php')) {
            require_once(__DIR__ . '/commands/' . $command . '.php');

            run($args, $message);
        } else {
            $message->reply("```Diff\n- This commands does not exist!```");
        }
    });
});

$discord->run();
