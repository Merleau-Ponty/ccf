<?php

$message = "";
$connexion = new BDD('ccf');
// On genere l'ordre insert qu'on execute dans mysql
// On prépare la requete à  éxecuter (recuperer touts les logins des professeurs)
$login = addslashes($_POST['username']);
$password = $_POST['password'];
$login = strtoupper($login);
// Sequence de cryptage de mot de passe
define("SALT", "<b}DKJ{]1QIcW<-`Kn|ENm(w1l9>epVQvkhyEaN*dfJEA{MZ");
// On hash les mots de passe de façon a ce qu'ils soient non décryptables
$pass = sha1(SALT . md5($password . SALT) . sha1(SALT));
//$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$requete = "select count(*) as nb from PROFESSEURS where LOGIN='$login' and MOT_DE_PASSE='$pass'";
try {
    $resultats = $connexion->select($requete);
}
//si probleme sur l'execution de la requête
catch (PDOException $e) {
    echo "Probleme pour acceder à la table organisateurs - abandon ";
    $erreur = $e->getCode();
    $message = $e->getMessage();
    echo "erreur $erreur $message\n";
    return;
}
//$resultats->setFetchMode(PDO::FETCH_ASSOC);
foreach ($resultats as $nombre) {
    $nb = $nombre['nb'];
}
//$nb=$resultats[$'nb'];
if ($nb == 0) {
    $message = "<center><FONT COLOR=#ff0000>mauvais identifiant / mot de passe</FONT></center>";
} else {
    if ($_SESSION['ccf_amj'] <= $_SESSION['current_amj']) {

        header("location:taborg_res.php?login=$login");
    } else {
        //avant la course
        header("location:taborg_val.php?login=$login");
    }
}
?>
