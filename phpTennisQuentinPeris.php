<?php

interface JoueurInterface {
    public function getNom();
    public function getPrenom();
    public function getClassement();
}

class Joueur implements JoueurInterface {
    private $nom;
    private $prenom;
    private $classement;

    public function __construct($nom, $prenom, $classement) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->classement = $classement;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getClassement() {
        return $this->classement;
    }
}

class Rencontre
{
    private $joueur1;
    private $joueur2;
    private $score;
    private $date;

    public function __construct(JoueurInterface $joueur1, JoueurInterface $joueur2, $score, $date)
    {
        $this->joueur1 = $joueur1;
        $this->joueur2 = $joueur2;
        $this->score = $score;
        $this->date = $date;
    }
}
class Tournoi {
    private $joueurs = [];
    private $matchs = [];

    public function ajouterJoueur(JoueurInterface $joueur) {
        $this->joueurs[] = $joueur;
    }

    public function modifierJoueur(int $index, JoueurInterface $joueur) {
        if (isset($this->joueurs[$index])) {
            $this->joueurs[$index] = $joueur;
        }
    }

    public function supprimerJoueur(int $index) {
        if (isset($this->joueurs[$index])) {
            unset($this->joueurs[$index]);
            $this->joueurs = array_values($this->joueurs);
        }
    }

    public function listerJoueurs(): array
    {
        return $this->joueurs;
    }

    public function creerMatch(JoueurInterface $joueur1, JoueurInterface $joueur2, string $score, \DateTime $date) {
        $this->matchs[] = new Rencontre($joueur1, $joueur2, $score, $date);
    }

    public function listerMatchs(): array
    {
        return $this->matchs;
    }
}

function afficherMenu() {
    echo "Menu :\n";
    echo "1. Ajouter un joueur\n";
    echo "2. Modifier un joueur\n";
    echo "3. Supprimer un joueur\n";
    echo "4. Lister les joueurs\n";
    echo "5. Créer un match\n";
    echo "6. Lister les matchs\n";
    echo "0. Quitter\n";
    echo "Choix : ";
}

$tournoi = new Tournoi();

while (true) {
    afficherMenu();
    $choix = trim(fgets(STDIN));

    switch ($choix) {
        case '1':
            echo "Nom du joueur : ";
            $nom = trim(fgets(STDIN));
            echo "Prénom du joueur : ";
            $prenom = trim(fgets(STDIN));
            echo "Classement du joueur : ";
            $classement = trim(fgets(STDIN));

            $joueur = new Joueur($nom, $prenom, $classement);
            $tournoi->ajouterJoueur($joueur);
            echo "Joueur ajouté avec succès.\n";
            break;
        case '2':
            echo "Indice du joueur à modifier : ";
            $indice = intval(trim(fgets(STDIN)));
            echo "Nom du joueur : ";
            $nom = trim(fgets(STDIN));
            echo "Prénom du joueur : ";
            $prenom = trim(fgets(STDIN));
            echo "Classement du joueur : ";
            $classement = trim(fgets(STDIN));

            $joueur = new Joueur($nom, $prenom, $classement);
            $tournoi->modifierJoueur($indice, $joueur);
            echo "Joueur modifié avec succès.\n";
            break;
        case '3':
            echo "Indice du joueur à supprimer : ";
            $indice = intval(trim(fgets(STDIN)));
            $tournoi->supprimerJoueur($indice);
            echo "Joueur supprimé avec succès.\n";
            break;
        case '4':
            $joueurs = $tournoi->listerJoueurs();
            foreach ($joueurs as $indice => $joueur) {
                echo "$indice. {$joueur->getNom()} {$joueur->getPrenom()} (Classement : {$joueur->getClassement()})\n";
            }
            break;
        case '5':
            echo "Indice du premier joueur : ";
            $indice1 = intval(trim(fgets(STDIN)));
            echo "Indice du deuxième joueur : ";
            $indice2 = intval(trim(fgets(STDIN)));
            echo "Score du match : ";
            $score = trim(fgets(STDIN));
            echo "Date du match (YYYY-MM-DD) : ";
            try {
                $date = new DateTime(trim(fgets(STDIN)));
            } catch (Exception $e) {
            }

            $joueurs = $tournoi->listerJoueurs();
            if (isset($joueurs[$indice1]) && isset($joueurs[$indice2])) {
                $joueur1 = $joueurs[$indice1];
                $joueur2 = $joueurs[$indice2];
                $tournoi->creerMatch($joueur1, $joueur2, $score, $date);
                echo "Match créé avec succès.\n";
            } else {
                echo "Les indices des joueurs sont invalides.\n";
            }
            break;
        case '6':
            $matchs = $tournoi->listerMatchs();
            foreach ($matchs as $indice => $match) {
                echo "$indice. {$match->getVainqueur()->getNom()} vs {$match->getVainqueur()->getPrenom()} (Score : {$match->getScore()}, Date : {$match->getDate()->format('Y-m-d')})\n";
            }
            break;
        case '0':
            echo "Au revoir !\n";
            exit;
        default:
            echo "Choix invalide.\n";
    }
}
