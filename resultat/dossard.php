<?php

// appel fichier pour connexion
include ('../include/BDD.php');
$connexion = new BDD('ccf'); // établit connexion
$num = $_POST['dossard'];

$requete = "SELECT NOM,PRENOM, ID_CLASSE FROM PARTICIPANTS WHERE DOSSARD=$num";

$resultats = $connexion->select($requete);
//$tab_V=$resultats->fetchALL(PDO::FETCH_ASSOC);

try {
    $resultats = $connexion->select($requete);
} catch (PDOException $e) {
    $message = "probleme pour acceder aux informations des participants<br/>";
    $message = $message . $e->getMessage();
}


foreach ($resultats as $ligne) {
    $participant = $participant . $ligne['NOM'] . " " . $ligne['PRENOM'] . " &emp&empClasse : " . $ligne['ID_CLASSE'] . " ";
// renvoyer la liste des voitures

    echo $participant;
    