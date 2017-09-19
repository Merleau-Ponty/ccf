<?php
include_once "../include/BDD.php";
$connexion= new BDD('ccf');
include('../include/header.php');
?>

    <body>
        <div class="main">
            <header>
                <!--<h1><a href="accueil.html"><img src="images/logo.png" alt=""></a></h1> -->
            </header>
            <!--==============================Contenu================================-->
            <?php include('../include/nav.php') ?>



                <!--====================================================-->

                <div class="cycle">
                    <br>
                    <a href="../include/csv.php?num_req=1">
                        <center>
                            <button class="waves-effect waves-light btn">Exporter au format csv</button>
                        </center>
                    </a>
                    <br/>
                    <center>
                        <a href="#" onclick="window.print(); return false;">
                            <button class="waves-effect waves-light btn">Imprimer cette page</button>
                        </a>
                    </center>

                    <?php
			$requete2 = "select sum(KMPARCOURUS) as TOTALDISTANCE, sum(MONTANT) as TOTALMONTANT , sum(MTREEL) as TOTALRECOLTE from PARTICIPANTS";
			try {
				$resultats2 = $connexion->select($requete2);
//                var_dump($resultats2);
			}
					//si probleme sur l'execution de la requête
			catch (PDOException $e) {
				echo "Probleme pour lister les participants - abandon ";
				$erreur = $e->getCode();
				$message = $e->getMessage();
				echo "erreur $erreur $message\n";
				return;
			}
					// on dit qu'on veut que le résultat soit récupérable sous forme de tableau)
//			$tableau2=$resultats2->fetchAll(PDO::FETCH_ASSOC);
			$totdist=$resultats2[0]['TOTALDISTANCE'];
			$totmont=round($resultats2[0]['TOTALMONTANT']);
            $totrecolte = round($resultats2[0]['TOTALRECOLTE']);
			echo "<h3><font color='red'><center>Total de kilomètres parcourus : ".$totdist." km<br/>Somme totale promise : ".$totmont." euros<br/>Somme totale récoltée : ".$totrecolte." euros<br/></center></font></h3><br/><br/>";


            $requete = "select NO_PARTICIPANT,NOM,PRENOM,ID_CLASSE,DOSSARD,KMPARCOURUS,PROMESSE, MONTANT from PARTICIPANTS
						where KMPARCOURUS<>0
						order by ID_CLASSE , NOM";
                  try 
                  {
                    $resultats = $connexion->select($requete);
                  }
                  //si probleme sur l'execution de la requête
                  catch (PDOException $e) {
                    echo "Probleme pour lister les participants - abandon ";
                    $erreur = $e->getCode();
                    $message = $e->getMessage();
                    echo "erreur $erreur $message\n";
                    return;
                  }
                  // on dit qu'on veut que le résultat soit récupérable sous forme de tableau)
            //      $tableau=$resultats->fetchALL(PDO::FETCH_ASSOC);
                  $sauveclasse=$resultats[0]['ID_CLASSE'];
                  $sauveclasse2=$resultats[0]['ID_CLASSE'];
			?>
                        <center>
                                        <?php
echo "
                         <ul class='collapsible' data-collapsible='accordion'>
                            <li>
                                <div class='collapsible-header'>$sauveclasse</div>
                                <div class='collapsible-body'>
                                <table>
                                <thead>
                                    <TR>
                                        <TH> Nom </TH>
                                        <TH> Prénom </TH>
                                        <TH> Classe </TH>
                                        <TH> Dossard </TH>
                                        <TH> Distance </TH>
                                        <TH> Promesse</TH>
                                        <TH> Montant </TH>
                                    </TR>
                                </thead>
                                </tbody>
                                    <tr>";
						/*$requete = "select NO_PARTICIPANT,NOM,PRENOM,ID_CLASSE,DOSSARD,KMPARCOURUS,MONTANT, MTREEL from PARTICIPANTS
						where KMPARCOURUS<>0
						order by ID_CLASSE , NOM";
				$resultats = $connexion->select($requete);*/
					// on dit qu'on veut que le résultat soit récupérable sous forme de tableau)
//						$tableau=$resultats->fetchAll(PDO::FETCH_ASSOC);
						foreach ($resultats as $tab_eleve) {
                            $no_eleve=$tab_eleve['NO_PARTICIPANT'];
                            echo "<input type=\"hidden\" value=",$tab_eleve['NO_PARTICIPANT']," name=\"NO_PARTICIPANT[]\">";
                            if($tab_eleve['ID_CLASSE']<>$sauveclasse)
                            {
                                $sauveclasse=$tab_eleve['ID_CLASSE'];
                                echo "</tbody></table></li><ul class='collapsible' data-collapsible='accordion'>
                            <li>
                                <div class='collapsible-header'>$sauveclasse</div>
                                <div class='collapsible-body'>
                                <table>
                                <thead>
                                    <TR>
                                        <TH> Nom </TH>
                                        <TH> Prénom </TH>
                                        <TH> Classe </TH>
                                        <TH> Dossard </TH>
                                        <TH> Distance </TH>
                                        <TH> Promesse</TH>
                                        <TH> Montant </TH>
                                    </TR>
                                </thead>
                                </tbody>
                                    <tr>";
                            }
							echo "<TR><TD>", $tab_eleve['NOM'], "</TD><TD>", $tab_eleve['PRENOM'], "</TD><TD>", $tab_eleve['ID_CLASSE'], "</TD><TD>",
							$tab_eleve['DOSSARD'],"</TD><TD>", $tab_eleve['KMPARCOURUS'],"</TD><TD>", $tab_eleve['PROMESSE'],"</TD><TD>", $tab_eleve['MONTANT'],"</TD>";
						}

						
                                    echo "</tbody></table></li>";
?>
                        </center>


                </div>
        </div>
        </section>

        <!--==============================footer=================================-->

        <?php
include('../include/footer.php');
?>