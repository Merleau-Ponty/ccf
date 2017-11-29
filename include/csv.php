<?php

#================== Connection base de données ==================
include ("../include/BDD.php");
$connexion = new BDD('ccf');

// Initialisaion des variable
$num_req = $_GET["num_req"];

#================== Si $num_req est 1 ==================
if ($num_req == 1) {

    // Nom des champs dans le CSV
    $xls_output = "Nom;Prenom;Classe;Dossard;Distance;Promesse;Montant";

    // Requete SQL avec les champ dans le même ordre que haut dessus
    $query = 'SELECT NOM,PRENOM,ID_CLASSE,DOSSARD,KMPARCOURUS,PROMESSE,MONTANT FROM PARTICIPANTS where KMPARCOURUS<>0 order by ID_CLASSE , NOM';
}

#================== Si $num_req est 2 ==================
if ($num_req == 2) {

    // Initialisation des variables
    $login = $_GET["login"];

    // Nom des champs dans le CSV
    $xls_output = "Nom;Prénom;Classe;Promesse de dons;Montant réel;";

    // Requete SQL avec les champ dans le même ordre que haut dessus
    $query = "SELECT NO_PARTICIPANT,PARTICIPANTS.NOM,PARTICIPANTS.PRENOM,PARTICIPANTS.ID_CLASSE,INSCRIT,AUTORISATION,MONTANT,MTREEL from PARTICIPANTS
	inner join CLASSE on PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
	inner join PROFESSEUR on PROFESSEUR.LOGIN = CLASSE.LOGIN
	where PROFESSEUR.LOGIN='$login' and INSCRIT='O' AND AUTORISATION='O'
	order by PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";
}

#================== Si $num_req est 2 ==================
if ($num_req == 3) {

    // Nom des champs dans le CSV
    $xls_output = "Numéro participants;Nom;Prénom;Classe;Nombre de kilomètres;Promesse de dons;Montant réel";

    // Requete SQL avec les champ dans le même ordre que haut dessus
    $query = "SELECT NO_PARTICIPANT, PARTICIPANTS.NOM,PARTICIPANTS.PRENOM, PARTICIPANTS.ID_CLASSE, KMPARCOURUS, MONTANT, MTREEL 
            FROM PARTICIPANTS
            WHERE INSCRIT != 'N' 
            AND  KMPARCOURUS IS NOT NULL
            ORDER BY PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";
}

// Saut de ligne dans le fichier CSV
$xls_output .= "\n";

// Execution de la requête
$resultats = $connexion->select($query);

// Pour chaque élèves
foreach ($resultats as $ligne) {
    // Pour chaque données de l'élève
    foreach ($ligne as $elt) {
        $xls_output .= "$elt;";
    }
    // Saut de ligne pour passer au prochain élève
    $xls_output .= "\n";
}

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=ccf_" . date("Ymd") . ".csv");
print $xls_output;
return;
?>