<?php 
include_once "../include/BDD.php";
$connexion = new BDD('ccf');
 // On récupére le login
if(isset($_GET['login']))
{
	ini_set('max_input_vars', 2600);
    $login = trim($_GET['login']);
	/*echo "<br/> memoire ",ini_get('memory_limit');
	ini_set('memory_limit','1024M');
	echo "<br/> memoire ",ini_get('memory_limit');*/
	//echo 'post_max_size+1 = ' . (ini_get('post_max_size')) . "<br/>";
	//echo 'upload_max_filesize = ' . (ini_get('upload_max_filesize')) . "<br/>";
	//phpinfo();
	
} 
else
{
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
						if(isset($_POST['NO_PARTICIPANT'])) 
                        {
						//print_r($_POST['NO_PARTICIPANT']);
						  // echo "taille maxi ",count($_POST['NO_PARTICIPANT']);
							for ($i = 0; $i < count($_POST['NO_PARTICIPANT']); $i++) 
                            {
							
								$no_eleve = $_POST['NO_PARTICIPANT'][$i];
								
								$mtreel = $_POST['MONTANTREEL'][$i];
								$anc_montant = $_POST['ANC_MONTANT'][$i];
								
								//echo "mtreel $mtreel anc_montant $anc_montant <br/>";
                                if($mtreel != $anc_montant)
								 if($mtreel != 0)
                                {
									
                                   $requete = "update PARTICIPANTS set MTREEL='$mtreel' where NO_PARTICIPANT='$no_eleve';";
								   //echo $requete,"<br/>";
								    $connexion->insert($requete); 
                                }
								
							}
							//echo "<br/>i ",$i;
							
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
                        <!--impression-->
                        <a href="../include/csv.php?num_req=2&login=<?php echo $login?>">
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
                        <form method="post" action="taborg_res.php?login=<?=$login?>">
                            <center>
                                <caption><strong><h3>Tableau récapitulatif des élèves</h3></strong></caption>
                                            <?php
                            
					echo"<input type='hidden' name='login' value=$login></input>";
				    $requete = "select NO_PARTICIPANT, PARTICIPANTS.NOM,PARTICIPANTS.PRENOM, PARTICIPANTS.ID_CLASSE, KMPARCOURUS, MONTANT, MTREEL  as ANC_MONTANT, MTREEL from PARTICIPANTS
									where INSCRIT != 'N' and  KMPARCOURUS is not NULL
									order by PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";
				    $resultats = $connexion->select($requete);
					// On dit qu'on veut que le résultat soit récupérable sous forme de tableau)
                    if(count($resultats) != 0)
                    {
                        // Récupération de la première classe
                        $sauveclasse = $resultats[0]['ID_CLASSE'];
                       
                        $cpt = 0;
                        echo "<ul class='collapsible' data-collapsible='accordion'>
                                <li>
                                    <div class='collapsible-header centrer'>$sauveclasse</div>
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
                        
                        foreach ($resultats as $tab_eleve)
                        {
                            $no_eleve=$tab_eleve['NO_PARTICIPANT'];
                            echo "<input type=\"hidden\" value=",$tab_eleve['NO_PARTICIPANT']," name=\"NO_PARTICIPANT[]\">";
							 echo "<input type=\"hidden\" value='",$tab_eleve['MTREEL'],"' name=\"ANC_MONTANT[]\">";
							
                            $cpt += 1;
                            if($tab_eleve['ID_CLASSE'] <> $sauveclasse)
                            {
                                $sauveclasse=$tab_eleve['ID_CLASSE'];
                                      echo "</div></tbody></table></li>
                                <li>
                                    <div class='collapsible-header centrer'>$sauveclasse</div>
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
                            echo "<tr><td>", $tab_eleve['NOM'], "</td><td >", $tab_eleve['PRENOM'], "</td><td>", $tab_eleve['ID_CLASSE'], "</td><td >",$tab_eleve['KMPARCOURUS'],"</td><td>",$tab_eleve['MONTANT'], "</td><td><input type='text' name='MONTANTREEL[]' value='{$tab_eleve['MTREEL']}' style='width:65px;'/></td></tr>";
                        }
                        echo "</tbody></table></li>";
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
                        $("button[name='Enregistrer']").click(function()
                        {
                            $('#form').submit();
                        });
                    });
                </script>