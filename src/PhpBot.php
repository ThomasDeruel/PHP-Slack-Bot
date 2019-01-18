<?php

/*
* Ajoutez ici les méthodes de votre bot !
*/
class PhpBot
{
    /**
     * @var bool $gameStatus        une partie a t-elle été commencée ?
     * @var array $charList         caractères du mot-clé à rechercher
     * @var string $reponse         mot-clé à rechercher
     * @var int $chances            nombre de chances
     * @var array $alreadyUsed      caractères déjà utilisés qui ne correspondent pas
     */
    private $gameStatus = false;
    private $charList;
    private $reponse;
    private $chances;
    private $alreadyUsed = [];

    /**
     * Démarrer une partie de pendu
     */
    public function demarrerPartie($bot)
    {
        $this->gameStatus = true;

        // Choix aléatoire du nom d'une fonction interne à PHP
        $functions     = get_defined_functions()['internal'];
        $index         = random_int(0, count($functions));
        $this->reponse = $functions[$index];

        // Transformer le nom de la fonction en tableau (1 élém = 1 char)
        $characters = str_split($this->reponse);

        // Réinitialisation de la propriété
        $this->charList = [];
        foreach ($characters as $char) {
            $this->charList[] = [
                'char'  => $char,
                'found' => false,
            ];
        }

        $this->chances = 10;

        $bot->reply('Démarrage de la partie !');
        $this->tourSuivant($bot);
    }


    /**
     * Obtenir le mot-clé selon les caractères devinés
     */
    private function obtenirMotCle() : string
    {
        $str = '';
        // Pour chaque caractère, l'afficher si trouvé, sinon mettre #
        foreach ($this->charList as $char) {
            if ($char['found']) {
                $str .= $char['char'];
            } else {
                $str .= '#';
            }
        }

        return $str;
    }

    /**
     * Proposer un caractère
     */
    public function proposerCaractere($bot, $char)
    {
        // Si aucune partie n'est démarrée
        if (!$this->gameStatus) {
            $bot->reply('Aucune partie n\'est en cours :-(');
            $bot->reply('Pour démarrer une partie: start');
            return;
        }

        // A t-on trouvé un caractère correspondant à la proposition ?
        $trouve = false;

        // foreach fait une copie du tableau,
        // on passe donc ses éléments par référence: &$var
        foreach ($this->charList as &$lettre) {
            // Si le caractère parcouru correspond à la proposition
            if ($lettre['char'] === $char) {
                $lettre['found'] = true;
                $trouve = true;
            }
        }

        // Si la proposition était correcte ...
        if ($trouve) {
            $bot->reply('Correct !');

        } else {    // Si non ...
            if (in_array($char, $this->alreadyUsed)) {
                $bot->reply('Ce caractère a déjà été proposé !');
            } else {
                $bot->reply('Mauvaise réponse ..');
                $this->chances--;
                $this->alreadyUsed[] = $char;
            }
        }

        // Soit fin de partie, ou tour suivant
        $this->tourSuivant($bot);
    }

    /**
     * Fin de partie / Game Over / tour suivant
     */
    private function tourSuivant($bot)
    {
        // Si on a gagné la partie
        $filtre = array_filter($this->charList, function ($elem) {
            return $elem['found'];
        });
        if (count($this->charList) === count($filtre)) {
            $this->gameStatus = false;
            $bot->reply('Gagné ! Vous avez trouvé la fonction ' . $this->reponse . '()');
            return;
        }

        // Si on a perdu la partie
        if ($this->chances === 0) {
            $this->gameStatus = false;
            $bot->reply('Perdu ! Il fallait trouver: ' . $this->reponse . '()');
            return;
        }

        $bot->reply('Fonction: ' . $this->obtenirMotCle());
        $bot->reply('Chances: ' . $this->chances);
    }

    /**
     * Faire une proposition de réponse complète
     */
    public function proposerReponse($bot, $prop)
    {
        if ($prop === $this->reponse) {
            $this->charList = array_map(
                function ($elem) {
                    $elem['found'] = true;
                    return $elem;
                },
                $this->charList
            );

        } else {
            $this->chances--;
            $bot->reply('Mauvaise réponse ...');
        }

        $this->tourSuivant($bot);
    }


    /**
     * Arrêter la partie en cours
     */
    public function arreterPartie($bot)
    {
        $this->gameStatus = false;
        $bot->reply('Partie terminée');
    }


    /**
     * Savoir si une partie est en cours
     */
    public function obtenirStatut($bot)
    {
        $bot->reply('Statut: ' . ($this->gameStatus ? 'on' : 'off'));
    }
}