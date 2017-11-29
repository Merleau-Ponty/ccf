<?php
include_once "../include/BDD.php";
$connexion = new BDD('ccf');
// On récupére le login
if (isset($_GET['login'])) {
    $login = trim($_GET['login']);
} else {
    $login = $_POST['login'];
}
?>

<?php include("../include/header.php") ?>

<body>
    <div class="main">
        <header>
            <!--<h1><a href="accueil.html"><img src="images/logo.png" alt=""></a></h1> -->
        </header>

<?php include("../include/nav.php") ?>

        <h1 class='center'>Dons</h1>
        <div class="cycle">
            <br>
            <center>
                <table class="centered">
                    <thead>
                        <tr>
                            <th>Total promis</th>
                            <th>Total récolté</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
// On fait la mise à jour avant
if (isset($_POST['NO_PARTICIPANT'])) {
    for ($i = 0; $i < count($_POST['NO_PARTICIPANT']); $i++) {
        $no_eleve = $_POST['NO_PARTICIPANT'][$i];
        $mtreel = $_POST['montantreel'][$i];
        $anc_montant = $_POST['ANC_MONTANT'][$i];
        if ($mtreel != $anc_montant) {
            $requete = "update PARTICIPANTS set MTREEL='$mtreel' where NO_PARTICIPANT='$no_eleve';";
            //echo $requete, "<br/>";
            $connexion->insert($requete);
        }
    }
}

$requete = "select sum(MONTANT) as mtp, sum(MTREEL) as mtr from PARTICIPANTS; ";
$resultats = $connexion->select($requete);
// On dit qu'on veut que le résultat soit récupérable sous forme de tableau)
// $tableau = $resultats->fetchAll(PDO::FETCH_ASSOC);
$mtp = floor($resultats[0]['mtp']);
$mtr = floor($resultats[0]['mtr']);
echo "<tr><td>$mtp</td><td>$mtr</td></tr>";
?>
                    </tbody>
                </table>
            </center>
            <br>
            <center><h1>Dons Classe</h1></center>
            <table class="centered">
                <thead>
                    <tr>
                        <th>Total promis</th>
                        <th>Total récolté</th>
                    </tr>
                </thead>
                <tbody>
<?php
$req = "SELECT PARTICIPANTS.ID_CLASSE, sum(MONTANT) as mtp, sum(MTREEL) as mtr 
                                from PARTICIPANTS 
                                inner join CLASSE on CLASSE.ID_CLASSE = PARTICIPANTS.ID_CLASSE 
                                inner join PROFESSEURS on CLASSE.LOGIN = PROFESSEURS.LOGIN 
                                WHERE CLASSE.ID_CLASSE IN (select ID_CLASSE from CLASSE where CLASSE.LOGIN = '$login')
                                GROUP BY PARTICIPANTS.ID_CLASSE;";
//print_r($req);
$res = $connexion->select($req);
// On dit qu'on veut que le résultat soit récupérable sous forme de tableau)
// $tableau = $resultats->fetchAll(PDO::FETCH_ASSOC);
$mtp_classe = floor($res[0]['mtp']);
$mtr_classe = floor($res[0]['mtr']);
echo "<tr><td>$mtp_classe</td><td>$mtr_classe</td></tr>";
?>
                </tbody>
            </table>

            <!--impression-->
            <a href="../include/csv.php?num_req=2&login=<?php echo $login ?>">
                <center>
                    <button class="waves-effect waves-light btn">Exporter au format csv</button>
                </center>
            </a>
            <br>
            <center>
                <button class="waves-effect waves-light btn" onclick="window.print(); return false;">Imprimer cette page</button>
            </center>
            <br>

            <!-- validation montant promis   -->
            <form method="post" action="tabprof_res.php">
                <center>
                    <caption><strong><h3>Tableau récapitulatif des élèves</h3></strong></caption>
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
                        <tbody>
                            <tr>

<?php
echo"<input type='hidden' name='login' value=$login></input>";


$requete = "SELECT NO_PARTICIPANT,PARTICIPANTS.NOM,PARTICIPANTS.PRENOM,PARTICIPANTS.ID_CLASSE, KMPARCOURUS, MONTANT, MTREEL ANC_MONTANT, MTREEL from PARTICIPANTS
									inner join CLASSE on PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
									inner join PROFESSEURS on CLASSE.LOGIN = PROFESSEURS.LOGIN
									where PROFESSEURS.LOGIN='$login'
                                    and INSCRIT = 'O'
									order by PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";

$resultats = $connexion->select($requete);
// On dit qu'on veut que le résultat soit récupérable sous forme de tableau)
if (count($resultats) != 0) {
    $sauveclasse = $resultats[0]['ID_CLASSE'];
    // Pour les terminales

    echo "<tr><td><strong>$sauveclasse</strong></td></tr> ";
    foreach ($resultats as $tab_eleve) {
        $no_eleve = $tab_eleve['NO_PARTICIPANT'];
        echo "<input type=\"hidden\" value=", $tab_eleve['NO_PARTICIPANT'], " name=\"NO_PARTICIPANT[]\">";
        /* on ne veut réécrire que les participants pour lesquels le montant réel a changé */
        echo "<input type=\"hidden\" value='", $tab_eleve['ANC_MONTANT'], "' name=\"ANC_MONTANT[]\">";
        if ($tab_eleve['ID_CLASSE'] <> $sauveclasse) {
            $sauveclasse = $tab_eleve['ID_CLASSE'];
            echo "<tr><td><strong>$sauveclasse</strong></td></tr> ";
        }
        echo "<tr><td>", $tab_eleve['NOM'], "</td><td>", $tab_eleve['PRENOM'], "</td><td><center>", $tab_eleve['ID_CLASSE'], "</center></td><td><center>", $tab_eleve['KMPARCOURUS'], "</td><td><center>", $tab_eleve['MONTANT'], "</td>", "<td><input type='text' name='montantreel[]' value='{$tab_eleve['MTREEL']}' style='width:65px;' /></td>";
    }
}
?>
                            </tr>
                        </tbody>
                    </table>
                </center>
                <br>
                <center>
                    <button name="Enregistrer" class='waves-effect waves-light btn'>Enregistrer</button>
                </center>
            </form>

        </div>
    </div>

    <!-- ============================== Footer ================================= -->
<?php include("../include/footer.php") ?>

    <script type='text/javascript'>
        $(document).ready(function ()
        {
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