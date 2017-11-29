<?php

class BDD {

    public $connexion;

    #========== Cette fonction permet de se connecter au SGBD et à une base de données ==========

    function __construct($nombdd) {

        $message = "";
        $PARAM_nom_bd = "ccf"; // ccf le nom de votre base de données
        $PARAM_utilisateur = 'root'; // ccf nom d'utilisateur pour se connecter
        $PARAM_mot_passe = ''; // ccfmerleau mot de passe de l'utilisateur pour se connecter
        $PARAM_hote = 'localhost'; // le chemin vers le serveur
        $pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES 'UTF8'"; //encodage utf-8
        try {
            $this->connexion = new PDO("mysql:host=$PARAM_hote;dbname=$PARAM_nom_bd", $PARAM_utilisateur, $PARAM_mot_passe, $pdo_options);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {

            $message = "probleme de connexion a la base : ";
            $message = $message . $e->getMessage();
            echo "message $message";
            return $message;
        }

        return $this->connexion;
    }

    #========== Cette fonction permet d'inserer une ligne dans la base ==========

    function insert($requete, $auto = 'n') {
        $message = "";
        try {
            $resultats = $this->connexion->exec($requete);
        } catch (PDOException $e) {
            $message = "probleme pour executer cette requete $requete : ";
            $message = $message . $e->getMessage();
            echo $message;
        }
        if ($auto == 'o') {
            $tab = $this->connexion->query('SELECT LAST_INSERT_ID() as last_id');
            $tab1 = $tab->fetchALL(PDO::FETCH_ASSOC);
            $last_id = $tab1[0]['last_id'];
            return $last_id;
        }

        return $message;
    }

    #========== Cette fonction renvoie le resultat d'une requete sous forme d'un tableau ==========

    function select($requete) {
        $message = "";
        try {
            $resultats = $this->connexion->query($requete);
            #========== On dit qu'on veut que le résultat soit récupérable sous forme de tableau ==========
            $tab = $resultats->fetchALL(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $message = "probleme pour executer cette requete $requete : ";
            $message = $message . $e->getMessage();
            echo $message;
        }

        return $tab;
    }

    function getConnexion() {
        $connexion = new BDD('btssio17_ccf');
    }

}

?>
