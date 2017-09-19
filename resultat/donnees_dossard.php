<?php
// appel fichier pour connexion
include ('../include/BDD.php');
$connexion = new BDD('ccf'); // établit connexion
$DOSSARD=$_POST['numdos'];


    $requete = "select NOM,PRENOM,ID_CLASSE from PARTICIPANTS where DOSSARD=$DOSSARD";

    $resultats=$connexion->select($requete);
//    $tab_V=$resultats->fetchALL(PDO::FETCH_ASSOC);

        try{
            $resultats=$connexion->select($requete);

        }
        catch(PDOException $e){
            $message="probleme pour acceder aux informations des participants<br/>";
            $message=$message.$e->getMessage();
            echo $message;
            return;
        }


    // encodage du tableau en format JSON
    $donneesJSON = json_encode($resultats);

    echo $donneesJSON;