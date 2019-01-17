<?php
require __DIR__ . '/../vendor/autoload.php';

use React\EventLoop\Factory;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Slack\SlackRTMDriver;

// Load driver
DriverManager::loadDriver(SlackRTMDriver::class);

$loop = Factory::create();
$botman = BotManFactory::createForRTM([
    'slack' => [
        'token' => 'JETON-D-API-DE-VOTRE-BOT',
    ],
], $loop);





// Dès que le bot entends le terme "PHP", il répond:
$botman->hears('.* PHP .*', function($bot) {
    $bot->reply('le PHP c\'est badass !! 🤘');
});





$loop->run();