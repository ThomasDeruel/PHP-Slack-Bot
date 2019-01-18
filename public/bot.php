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
        'token' => 'xoxb-439139036022-527039546482-Xa9sXow6JAetGvmqTzdtSaCW',
    ],
], $loop);





// Dès que le bot entends le terme "PHP", il répond:
$botman->hears('.* PHP .*', function($bot) {
    $bot->reply('le PHP c\'est badass !! 🤘');
});

$phpBot = new PhpBot();

$botman->hears('start', [$phpBot, 'demarrerPartie']);
$botman->hears('stop', [$phpBot, 'arreterPartie']);
$botman->hears('status', [$phpBot, 'obtenirStatut']);

$botman->hears('char (.)', [$phpBot, 'proposerCaractere']);
$botman->hears('reponse (.+)', [$phpBot, 'proposerReponse']);






$loop->run();