<?php

/*
* Ajoutez ici les méthodes de votre bot !
*/
class PhpBot
{
    /**
     * @var bool $statut    Une partie à commencer si true, sinon false
     * @var string $reponse
     * @var array $charlist
     * @var int $chances
     * @var array $erreurs
     */
    private $statut = false;
    private $reponse;
    private $charList;
    private $chance;
    private $erreurs;
    public function demarrerPartie($bot)
    {
        $this->statut = true;

        //Choix aléatoire de la fonction à faire deviner
        $fonctions = get_defined_functions()['internal'];
        $index = random_int(0, count($fonctions));
        $this->reponse = $fonctions[$index];

        // Remettre à 0 la liste des caractères
        $this->charList = [];
        $this->chances = 10;
        // Casser la réponse par caractères
        $characters = str_split($this->reponse);
        foreach ($characters as $char) {
            $this->charList[] = [
                'char'  =>$char,
                'found' => false,
            ];
        }
        $bot->reply('HuMMMMMM on DEMARRe lAAA PaArRtIee ' . $this->reponse);
        $this->tourSuivant($bot);
    }
    
    public function obtenirMotCle() : string 
    {
        $str = '';

        foreach($this->charList as $char) {
            if($char['found']){
                $str .= $char['char'];
            } else {
                $str .= '#';
            }
        }

        return $str;
    }
    public function proposerCaractere($bot, $char) {
        if($this->statut === false) {
            $bot->reply('Aucune partie n\'est en cours ! :-(');
            $bot->reply('Pour démarrer: start');
            return;
        }

        $trouve = false;

        foreach($this->charList as &$lettre){
            if($lettre['char'] === $char){
                $lettre['found'] = true;
                $trouve          = true;
            }
        }
        //Si la proposition etait correcte
        if  ($trouve) {
            $bot->reply('Correct !');

            //Si non ...
        } else {
            if(in_array($char, $this->erreurs)) {
                $bot->reply('Ce caractère a déjà été proposé !');
            } else {
                $this->erreurs[] = $char;
                $this->chances--;
                $bot->reply('Mauvaise réponse ...');
            }

        }
        $this->tourSuivant($bot);
    }
    
    public function proposerReponse($bot, $prop) {
        if($prop !== $this->reponse) {
            $this->chances--;
            $bot->reply('Mauvaise réponse ...');
        } else {
            $this->charList = array_map(function ($elem) {
                $elem['found'] = true;
                return $elem;
            }, $this->charList);
        }
        $this->tourSuivant($bot);
    }

    private function tourSuivant($bot)
    {
        $filtre = array_filter($this->charList, function($elem){
            return $elem['found'];
        });
        if (count($this->charList) === count($filtre)) {
            $this->statut = false;
            $bot->reply('Gagné ! Vous avez trouvé la fonction ' . $this->reponse . '()');
            $link  = "http://php.net/manual/fr/function.";
            $link .= str_replace('_','-',$this->reponse) . '.php';
            $bot->reply('Documentation: ' . $link);
            return;
        }
        if($this->chances === 0) {
            $this->statut = false;
            //$reponse = implode('', array_column($this->charList, 'char'));
            $bot->reply('Perdu ! La fonction était: ' . $this->reponse. '()');
            $link  = "http://php.net/manual/fr/function.";
            $link .= str_replace('_','-',$this->reponse) . '.php';
            $bot->reply('Documentation: ' . $link);
            return;
        }
        $bot->reply('Fonction: ' . $this->obtenirMotCle());
        $bot->reply('Chance: ' . $this->chances);
    }
    public function arreterPartie($bot)
    {
        $this->statut = false;
        $bot->reply('HUUUUUUUUMMMMM Arrêt de la partie ! ');
    }

    public function obtenirStatut($bot)
    {
        $bot->reply('Statut: ' . ($this->statut ? 'on' : 'off'));
    }
}