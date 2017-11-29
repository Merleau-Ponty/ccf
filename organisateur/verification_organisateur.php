<?php

$connexion = new BDD('ccf');

// Initialisation des variable
$message = "";
$nb = 0;
$login = addslashes($_POST['username']);
$password = $_POST['password'];

// Séquence de cryptage pour le mot de passe
if (!defined('SALT'))
    define("SALT", "<b}DKJ{]1QIcW<-`Kn|ENm(w1l9>epVQvkhyEaN*dfJEA{MZ");
// On hash le mot de passe de façon a ce qu'il soit non décryptable
$pass = sha1(SALT . md5($password . SALT) . sha1(SALT));

// Requete pour recuperer les données du professeur
$requete = "SELECT COUNT(*) AS nb FROM PROFESSEURS WHERE LOGIN='$login' AND MOT_DE_PASSE='$pass'";

// On utilise un try catch pour récuperer les erreurs 
try {
    // Execution de la requête
    $resultats = $connexion->select($requete);
} catch (PDOException $e) {
    // Affichage des l'erreurs
    echo "Probleme pour acceder à la table organisateurs - abandon ";
    $erreur = $e->getCode();
    $message = $e->getMessage();
    echo "erreur $erreur $message\n";
    return;
}

// Pour chaque résultat trouver dans la base
foreach ($resultats as $nombre) {
    // Initialisation des variable
    $nb = $nombre['nb'];
}

// Si le nombre de résultat trouver et de 0
if ($nb == 0) {
    $message = "<center><FONT COLOR=#ff0000>mauvais identifiant / mot de passe</FONT></center>";
} else {
    // Le jour de la course et après
    if ($_SESSION['ccf_amj'] <= $_SESSION['current_amj']) {

        header("location:taborg_res.php?login=$login");
    }
    // Avant la course
    else {
        header("location:taborg_val.php?login=$login");
    }
}
?>
