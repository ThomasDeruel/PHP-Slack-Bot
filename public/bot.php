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
        'token' => 'xoxb-527238373074-527075155572-4I1Fl9oHAC6keYKAv3ImiX2k',
    ],
], $loop);


// Dès que le bot entends le terme "PHP", il répond:
$botman->hears('.* PHP .*', function($bot) {
    $bot->reply('le PHP c\'est badass !! 🤘');
});

$phpBot = new phpBot();

$botman->hears('start', [$phpBot, 'demarrerPartie']);
$botman->hears('stop', [$phpBot, 'arreterPartie']);
$botman->hears('statut', [$phpBot, 'obtenirStatut']);

$botman->hears('lettre (.)', [$phpBot, 'proposerCaractere']);
$loop->run();