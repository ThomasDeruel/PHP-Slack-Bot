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
     */
    private $statut = false;
    private $reponse;
    private $charList;
    private $chance;

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
        $bot->reply('Fonction: ' . $this->obtenirMotCle());
        $bot->reply('Chance:' . $this->chances);
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
        $bot->reply('Envoi: ' . $char);
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