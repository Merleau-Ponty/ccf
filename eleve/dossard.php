<?php
#================== Connection base de données ==================
include('../include/header.php');
include('../include/BDD.php');
$connexion = new BDD('ccf');
global $connexion;

#================== Récuparation des données de tout les inscrit ==================
// Requête pour récupérer les données de tout les inscrit
$requete = "SELECT * FROM PARTICIPANTS WHERE trim(INSCRIT)!='N' ORDER BY NOM,PRENOM";

// On utilise un try catch pour récuperer les erreurs
try {
    //Execution de la requête
    $resultats = $connexion->select($requete);
} catch (PDOException $e) {
    // Affichage des l'erreurs
    echo "Probleme pour lister les participants - abandon ";
    $erreur = $e->getCode();
    $message = $e->getMessage();
    echo "erreur $erreur $message\n";
    return;
}

#================== Boucle pour affecter chaque joueur à un numéro et une couleur de dossard ==================
// Initialisation des variables
$cpt = 1;
$couleur = '';

// $dossard_eleve servira pour l'affichage des resultat sous la forme d'un tableau
$dossard_eleve = '
				<table class="centered">
					<tr class="first_row">
						<td>
							<b>Numéro élève</b>
						</td>
						<td>
							<b>Nom</b>
						</td>
						<td>
							<b>Numéro dossard</b>
						</td>
						<td>
							<b>Couleur dossard</b>
						</td>
					</tr>';

// Boucle pour chaque resultat de la requête on affecte une couleur est un numéro
foreach ($resultats as $tab_eleve) {
    if ($cpt <= 100) {
        $couleur = 'Rouge';
    }
    if ($cpt > 100 && $cpt <= 200) {
        $couleur = 'Vert';
    }
    if ($cpt > 200 && $cpt <= 300) {
        $couleur = 'Bleu';
    }
    if ($cpt > 300 && $cpt <= 400) {
        $couleur = 'Jaune';
    }
    if ($cpt > 400 && $cpt <= 500) {
        $couleur = 'Blanc';
    }
    if ($cpt > 500 && $cpt <= 600) {
        $couleur = 'Rouge';
    }
    if ($cpt > 600 && $cpt <= 700) {
        $couleur = 'Vert';
    }

    $no_eleve = $tab_eleve['NO_PARTICIPANT'];

    $dossard_eleve .= "
    				<tr>
    					<td>
    						" . $tab_eleve['NO_PARTICIPANT'] . "
    					</td>
    					<td>
    						" . $tab_eleve['NOM'] . "
    					</td>
    					<td>
    						" . $cpt . "
    					</td>
    					<td>
    						'" . $couleur . "'
    					</td>
    				</tr>";

    // Requête pour mettre à jour le numéro du dossard				
    $requete = "update PARTICIPANTS set DOSSARD=$cpt, COULEUR='$couleur' where trim(NO_PARTICIPANT)=trim($no_eleve)";

    // On utilise un try catch pour récuperer les erreurs
    try {
        // Execution de la requête
        $connexion->insert($requete);
    } catch (PDOException $e) {
        // Affichage des erreurs
        echo "Probleme pour updater les participants - abandon ";
        $erreur = $e->getCode();
        $message = $e->getMessage();
        echo "erreur $erreur $message\n";
        return;
    }
    $cpt++;
}
$dossard_eleve .= "</table>";
?>

<body>
    <div class="main">
        <!-- =================== Menu =================== -->
        <?php include("../include/nav.php"); ?>

        <!-- =================== Authentitification =================== -->
        <center><h2>Affectation des dossards</h2></center>
        <div class="row">
            <?= $dossard_eleve ?>
        </div>

        <!-- =================== Footer =================== -->
        <?php include('../include/footer.php') ?>



