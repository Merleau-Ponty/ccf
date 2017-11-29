<?php
#================== Header et Connection base de données ==================
include("../include/header.php");
include("../include/BDD.php");
$connexion = new BDD('ccf');

// Initialisation des variables
$table_eleve = "";


#================== Si le login est dans l'URL (premier chargement de la page) ==================
if (isset($_GET['login'])) {
    ini_set('max_input_vars', 2600);

    // Initialisation des variable
    $login = trim($_GET['login']);
    /* echo "<br/> memoire ",ini_get('memory_limit');
      ini_set('memory_limit','1024M');
      echo "<br/> memoire ",ini_get('memory_limit'); */
    //echo 'post_max_size+1 = ' . (ini_get('post_max_size')) . "<br/>";
    //echo 'upload_max_filesize = ' . (ini_get('upload_max_filesize')) . "<br/>";
    //phpinfo();
}
#================== Il n'y a plus le login dans l'url alors on la mit dans un input pour le recuperer ==================
else {
    // Initialisation des variable
    $login = $_POST['login'];
}


#================== Si le formulaire à été envoyer ==================
if (isset($_POST['NO_PARTICIPANT'])) {
    //print_r($_POST['NO_PARTICIPANT']);
    //echo "taille maxi ",count($_POST['NO_PARTICIPANT']);
    // Pour chaque participants
    for ($i = 0; $i < count($_POST['NO_PARTICIPANT']); $i++) {
        // Initialisation des variables
        $no_eleve = $_POST['NO_PARTICIPANT'][$i];
        $mtreel = $_POST['MONTANTREEL'][$i];
        $anc_montant = $_POST['ANC_MONTANT'][$i];

        // Si Montant reel est different de Ancien montant
        if ($mtreel != $anc_montant) {
            // Si Montant reel est different de 0
            if ($mtreel != 0) {
                // Requête pour mettre à jour le montant du participants
                $requete = "UPDATE PARTICIPANTS SET MTREEL='$mtreel' WHERE NO_PARTICIPANT='$no_eleve';";

                // Execution de la requête
                $connexion->insert($requete);
            }
        }
    }
}


#================== Total promis et Total récolté ==================
// Requête pour récuper le total promis et le total récolté
$requete = "SELECT sum(MONTANT) AS mtp, sum(MTREEL) AS mtr FROM PARTICIPANTS; ";

// Execution de la requête
$resultats = $connexion->select($requete);

// Affectation des données récuperé par la requête
$mtp = floor($resultats[0]['mtp']);
$mtr = floor($resultats[0]['mtr']);

// Variable pour inséré dans le HTML les résultats
$totaux = "
        <tr>
            <td>
                $mtp
            </td>
            <td>
                $mtr
            </td>
        </tr>";


#================== Tableau d'affichage des élève ==================
$table_eleve = "<input type='hidden' name='login' value=$login>";

// Requête pour récuperer les information des élèves
$requete = "
            SELECT NO_PARTICIPANT, PARTICIPANTS.NOM,PARTICIPANTS.PRENOM, PARTICIPANTS.ID_CLASSE, KMPARCOURUS, MONTANT, MTREEL  AS ANC_MONTANT, MTREEL 
            FROM PARTICIPANTS
            WHERE INSCRIT != 'N' 
            AND  KMPARCOURUS IS NOT NULL
            ORDER BY PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";

// Execution de la requête
$resultats = $connexion->select($requete);

// Si le nombre d'élève est differnet de 0
if (count($resultats) != 0) {
    // Récupération de la première classe
    $sauveclasse = $resultats[0]['ID_CLASSE'];

    $cpt = 0;
    $table_eleve .= "
        <ul class='collapsible' data-collapsible='accordion'>
            <li>

                <!-- =================== Classe =================== -->
                <div class='collapsible-header centrer'>
                    $sauveclasse
                </div>


                <!-- =================== Entete du tableau =================== -->
                <div class='collapsible-body'>
                    <table class='centered'>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Classe</th>
                                <th>Nombre de kilomètres</th>
                                <th>Promesse</th>
                                <th>Dons</th>
                            </tr>
                        </thead>
                        <tbody>";

    // Pour chaque résultats
    foreach ($resultats as $tab_eleve) {
        $no_eleve = $tab_eleve['NO_PARTICIPANT'];
        $table_eleve .= "<input type='hidden' value='" . $tab_eleve['NO_PARTICIPANT'] . "' name='NO_PARTICIPANT[]'>";
        $table_eleve .= "<input type='hidden' value='" . $tab_eleve['MTREEL'] . "' name='ANC_MONTANT[]'>";

        $cpt += 1;
        if ($tab_eleve['ID_CLASSE'] <> $sauveclasse) {
            $sauveclasse = $tab_eleve['ID_CLASSE'];
            $table_eleve .= "
                        </tbody>
                    </table>
                </div>
            </li>
            <li>

                <!-- =================== Classe =================== -->
                <div class='collapsible-header centrer'>
                    $sauveclasse
                </div>

                <!-- =================== Entete du tableau =================== -->
                <div class='collapsible-body'>
                    <table  class='centered'>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Classe</th>
                                <th>Nombre de kilomètres</th>
                                <th>Promesse</th>
                                <th>Dons</th>
                            </tr>
                        </thead>
                        <tbody>";
        }
        $table_eleve .= "
                            <tr>
                                <td>" . $tab_eleve['NOM'] . "</td>
                                <td >" . $tab_eleve['PRENOM'] . "</td>
                                <td>" . $tab_eleve['ID_CLASSE'] . "</td>
                                <td >" . $tab_eleve['KMPARCOURUS'] . "</td>
                                <td>" . $tab_eleve['MONTANT'] . "</td>
                                <td><input type='text' name='MONTANTREEL[]' value='{$tab_eleve['MTREEL']}' style='width:65px;'/></td>
                            </tr>";
    }
    $table_eleve .= "
                        </tbody>
                    </table>
                </div>
            </li>";
}
?>

<!-- =================== Contenu de la page =================== -->
<body>
    <div class="main">
        <!-- =================== Menu =================== -->
        <?php include("../include/nav.php") ?>

        <h1 class='center'>Dons</h1>
        <div class="cycle">

            <!-- =================== Totaux =================== -->
            <div>
                <center> 
                    <table class="centered">
                        <thead>
                            <tr>
                                <th>Total promis</th>
                                <th>Total récolté</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $totaux ?>
                        </tbody>
                    </table>
                </center><br>
            </div>

            <!-- =================== Exporter au format CSV =================== -->
            <div> 
                <a href="../include/csv.php?num_req=3&login=<?php echo $login ?>">
                    <center>
                        <button class="waves-effect waves-light btn">Exporter au format csv</button>
                    </center>
                </a><br>
            </div>

            <!-- =================== Imprimer =================== -->
            <div>
                <center>
                    <button class="waves-effect waves-light btn" onclick="window.print(); return false;">Imprimer cette page</button>
                </center>
            </div>

            <!-- =================== Tableau récaptitulatif des dons =================== -->
            <div>
                <form method="post" action="taborg_res.php?login=<?= $login ?>">
                    <center>
                        <caption>
                            <strong><h3>Tableau récapitulatif des élèves</h3></strong>
                        </caption>

                        <?= $table_eleve ?>
                    </center>
                    <br>
                    <center>
                        <button name="Enregistrer" class='waves-effect waves-light btn'>Enregistrer</button>
                    </center>
                </form>
            </div>
        </div>
    </div>

    <!-- =================== Footer =================== -->
    <?php include("../include/footer.php") ?>


    <script type='text/javascript'>
        $(document).ready(function () {

            // Modification de la bulle d'information selon la date
            var d = new Date();
            courante = new Date(d.getFullYear(), d.getMonth(), d.getDate());
            // Evaluation des chaînes de caractères en code pour être réutilisables
            eval(sessionStorage.ccf);
            eval(sessionStorage.courante);
            if (courante < ccf) {
                //location.href = "taborg_val.php?login=<?= $login ?>";
            }

            $('.collapsible').collapsible(
                    {
                        accordion: false
                    });
            $('.datepicker').pickadate(
                    {
                        selectMonths: true, // Menu déroulant pour les mois
                        selectYears: 15 // Nombre d'années possibles
                    });
            $("button[name='Enregistrer']").click(function ()
            {
                $('#form').submit();
            });
        });
    </script>