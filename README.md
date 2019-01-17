# Votre bot PHP pour Slack

**Pré-requis:**
 - Détenir un espace [Slack](https://slack.com)
 - Avoir installé [Composer](https://getcomposer.org/)


## Installation

### Installer les dépendances avec Composer

Nous allons avoir besoin de plusieurs outils:
 - **Botman** avec le [Slack driver](https://packagist.org/packages/botman/driver-slack) afin de communiquer avec **Slack**
 - un [client Slack](https://packagist.org/packages/mpociot/slack-client) pour l'API en temps réel *(utilise* **ReactPHP** *)*
 - l'*autoloader* généré par **Composer** permettra de charger toutes les classes perso situées dans `/src/`

Executez la commande d'installation de **Composer**:
```sh
composer install
```

### Ajouter le bot à votre espace Slack

Rendez-vous sur la page:
>https://[mon-espace].slack.com/apps/build/custom-integration

Sélectionnez *"Bots"*, donnez un nom à votre bot puis cliquez *"Ajouter une intégration de bot"*.

Vous trouverez ensuite votre **jeton d'API** en début de la page suivante (`Paramètres d'intégration > Jeton d'API`).

Copiez votre jeton et remplacez le placeholder:
```php
<?php
require __DIR__ . '/../vendor/autoload.php';

/* ... */

$botman = BotManFactory::createForRTM([
    'slack' => [
        'token' => 'JETON-D-API-DE-VOTRE-BOT',
    ],
], $loop);
```

Dans votre espace **Slack**, n'oubliez pas d'ajouter votre bot (`Inviter d'autres personnes à rejoindre cette chaîne` puis entrez le nom du bot).

### Tester l'intégration

Pour connecter votre bot à votre espace **Slack**:
```sh
php public/bot.php
```

Vous devriez voir apparaître le message suivant:
```sh
Successfully connected
```
>Pour arrêter le bot: `Ctrl+C`

Le bot en l'état est configuré pour envoyer un message dès qu'il voit le terme "***PHP***", essayez donc un message du genre:
```
C'est quoi déjà le PHP ?
```