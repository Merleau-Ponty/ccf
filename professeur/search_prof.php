<?php

session_start();
include '../include/BDD.php';
$connexion = new BDD('ccf');

if (isset($_SESSION['login'])) {
    $req = "select NOM_PROFESSEUR, PRENOM_PROFESSEUR from PROFESSEUR where LOGIN='" . $_SESSION['login'] . "'";

    try {
        $res = $connexion->select($req);
    } catch (PDOException $e) {
        echo "Probleme pour acceder à la table professeur - abandon ";
        $erreur = $e->getCode();
        $message = $e->getMessage();
        echo "erreur $erreur $message\n";
        return;
    }

    $donneesJSON = json_encode($res);

    echo $donneesJSON;
}
?>