<?php
include_once "../include/BDD.php";
$connexion = new BDD('ccf');
include('../include/header.php');
?>

<body>
    <div class="main">
        <header>
            <!--<h1><a href="accueil.html"><img src="images/logo.png" alt=""></a></h1> -->
        </header>
        <!--==============================Contenu================================-->
        <?php include('../include/nav.php') ?>



        <!--=====================================================================-->

        <div class="cycle">
            <br>
            <!--Bouton exporter au format csv-->
            <a href="../include/csv.php?num_req=1">
                <center>
                    <button class="waves-effect waves-light btn">Exporter au format csv</button>
                </center>
            </a>
            <br/>
            <!--Bouton Imprimer cette page-->
            <center>
                <a href="#" onclick="window.print(); return false;">
                    <button class="waves-effect waves-light btn">Imprimer cette page</button>
                </a>
            </center>


            <?php
            //Requete pour afficher le total de kilometre, le total de somme promise et le total de somme recoltée
            $requete1 = "SELECT SUM(KMPARCOURUS) AS TOTALDISTANCE, SUM(MONTANT) AS TOTALMONTANT , SUM(MTREEL) AS TOTALRECOLTE FROM PARTICIPANTS";


            $requete4 = "SELECT NOM,PRENOM, SUM(PROMESSE*KMPARCOURUS) as PRIX
						FROM PARTICIPANTS
						WHERE PROMESSE IS NOt NULL
						GROUP BY NOM, PRENOM
						HAVING PRIX >= (SELECT MAX(PROMESSE*KMPARCOURUS) FROM PARTICIPANTS)";

            $requete5 = "SELECT CLASSE.NO_COURSE, SUM(PARTICIPANTS.MTREEL)
                        FROM PARTICIPANTS, CLASSE
                        WHERE PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
                        AND PARTICIPANTS.PROMESSE IS NOT NULL
                        GROUP BY CLASSE.NO_COURSE
                        HAVING SUM(PARTICIPANTS.MTREEL) >= ALL(SELECT SUM(PARTICIPANTS.MTREEL)
                                                                FROM PARTICIPANTS, CLASSE
                                                                WHERE PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
                                                                AND PARTICIPANTS.PROMESSE IS NOT NULL
                                                                GROUP BY CLASSE.NO_COURSE)";

            //Try/catch pour reperer les erreur de la base
            try {
                $resultats1 = $connexion->select($requete1);
                $resultats4 = $connexion->select($requete4);
                $resultats5 = $connexion->select($requete5);
            //var_dump($resultats1);
            }
            //si probleme sur l'execution de la requête
            catch (PDOException $e) {
                echo "Probleme pour lister les participants - abandon";
                $erreur = $e->getCode();
                $message = $e->getMessage();
                echo "erreur $erreur $message\n";
                return;
            }

            //Recuperation des données de la requete4 dans des varible
            $totdist = $resultats1[0]['TOTALDISTANCE'];
            $totmont = round($resultats1[0]['TOTALMONTANT']);
            $totrecolte = round($resultats1[0]['TOTALRECOLTE']);

            //Recuperation des données de la requete4 dans les variable
            $promdonNom = $resultats4[0]['NOM'];
            $promdomPren = $resultats4[0]['PRENOM'];
            $promdonProm = $resultats4[0]['PRIX'];

            //Recuperation des données de la requete4 dans les variable
            $numerocourse = $resultats5[0]['NO_COURSE'];
            if ($numerocourse == NULL){
            	$numerocourse = "Indépendants";
            }
            $montantreel = $resultats5[0]['SUM(PARTICIPANTS.MTREEL)'];


            //Affichage du total de kilometre, du total de somme promise et du total de somme recoltée
            echo "
            	<h4>
            		<font color='red'>
            			<center>
                            Total de kilomètres parcourus : " . $totdist . " km<br>
                            Somme totale promise : " . $totmont . " euros<br>
                            Somme totale récoltée : " . $totrecolte . " euros<br>
                            Promesse de dons la plus importante : " . $promdonNom . " " . $promdomPren . " " . $promdonProm . "€<br>
                            Course qui a le plus rapporter : Course N°" . $numerocourse . " avec " . $montantreel . "€<br>
            			</center>
            		</font>
                </h4><br><br>";


			//Requete pour afficher les données de chaque participants
            $requete2 = "SELECT NO_PARTICIPANT, NOM, PRENOM, ID_CLASSE, DOSSARD, KMPARCOURUS, PROMESSE, MONTANT
            			FROM PARTICIPANTS
						WHERE KMPARCOURUS <> 0
						ORDER BY ID_CLASSE, NOM";

			//Try/catch pour reperer les erreur de la base
            try {
                $resultats2 = $connexion->select($requete2);
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
            //      $tableau2=$resultats2->fetchALL(PDO::FETCH_ASSOC);
            $sauveclasse = $resultats2[0]['ID_CLASSE'];
            $sauveclasse2 = $resultats2[0]['ID_CLASSE'];


            //Requetes pour afficher le nombre de participant, le montant des gains et les kilometres parcourus par course
            $requete3 = "SELECT CLASSE.NO_COURSE, COUNT(NO_PARTICIPANT) as NBPARTICIPANT, SUM(MTREEL) AS MTGAINSTOTAL, SUM(KMPARCOURUS) as KMPARCOURUSTOTAL
                        FROM PARTICIPANTS, CLASSE
                        WHERE PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
                        AND PROMESSE IS NOT NULL
                        GROUP BY CLASSE.NO_COURSE;";

            //Try/catch pour reperer les erreur de la base
            try {
                $resultats3 = $connexion->select($requete3);
            }
            //si probleme sur l'execution de la requête
            catch (PDOException $e) {
                echo "Probleme pour lister les courses - abandon ";
                $erreur = $e->getCode();
                $message = $e->getMessage();
                echo "erreur $erreur $message\n";
                return;
            }
            ?>


            <center>
                <?php
                //Affichage des course avec les information recuperer dans la $requete3
                echo "
					<ul class='collapsible' data-collapsible='accordion'>
                      	<li>
                        	<div class='collapsible-header'>
                        		Courses
                        	</div>
                        	<div class='collapsible-body'>
                        		<table>
                        			<thead>
                            			<tr>
	                            			<th> Course </th>
	                            			<th> Nombre de participants </th>
	                            			<th> kilometres parcourus </th>
	                            			<th> Gains total </th>
	                            		</tr>
                        			</thead>
                        			<tbody>";
                 				foreach ($resultats3 as $tab_course){
                                    if($tab_course['NO_COURSE'] == NULL){
                                        $tab_course['NO_COURSE'] = 'Indépendants';
                                    }
                     				echo "
                     					<tr>
                     						<td>",$tab_course['NO_COURSE'], "</td>
                     						<td>",$tab_course['NBPARTICIPANT'],"</td>
                     						<td>",$tab_course['KMPARCOURUSTOTAL'],"</td>
                     						<td>",$tab_course['MTGAINSTOTAL'],"</td>
                     					</tr>";
                				}
                				echo "
                					</tbody>
                				</table>
                			</div>
                		</li>";


                //Affichage des participants et de leur information par classe à l'aide des information recuperer dans la $requetes2
                echo "
                        <li>
                            <div class='collapsible-header'>
                            	$sauveclasse
                            </div>
                            <div class='collapsible-body'>
                            	<table>
                            		<thead>
                                		<tr>
                                    		<th> Nom </th>
                                        	<th> Prénom </th>
                                        	<th> Classe </th>
                                        	<th> Dossard </th>
                                        	<th> Distance </th>
                                        	<th> Promesse</th>
                                        	<th> Montant </th>
                                    	</tr>
                                	</thead>
                            		<tbody>";
                            		//Pour chaque elève
                					foreach ($resultats2 as $tab_eleve) {
                    					$no_eleve = $tab_eleve['NO_PARTICIPANT'];
                    					echo "<input type=\"hidden\" value=", $tab_eleve['NO_PARTICIPANT'], " name=\"NO_PARTICIPANT[]\">";
                    					//Si c'est une nouvelle classe on ferme tout et on recreer
                    					if ($tab_eleve['ID_CLASSE'] <> $sauveclasse) {
                        					$sauveclasse = $tab_eleve['ID_CLASSE'];
                        				echo "
                        			</tbody>
                        		</table>
                        	</li>
                        	<li>
                            	<div class='collapsible-header'>
                            		$sauveclasse
                            	</div>
                            	<div class='collapsible-body'>
                                	<table>
                                		<thead>
                                    		<tr>
                                        		<th> Nom </th>
                                        		<th> Prénom </th>
                                        		<th> Classe </th>
                                        		<th> Dossard </th>
                                       			<th> Distance </th>
                                        		<th> Promesse</th>
                                        		<th> Montant </th>
                                    		</tr>
                                		</thead>
                              			<tbody>";
                    					}
                    				echo "
                    						<tr>
                    							<td>", $tab_eleve['NOM'], "</td>
                    							<td>", $tab_eleve['PRENOM'], "</td>
                    							<td>", $tab_eleve['ID_CLASSE'], "</td>
                    							<td>", $tab_eleve['DOSSARD'], "</td>
                    							<td>", $tab_eleve['KMPARCOURUS'], "</td>
                    							<td>", $tab_eleve['PROMESSE'], "</td>
                    							<td>", $tab_eleve['MONTANT'], "</td>
                    						</tr>";
                					}
                					echo "
                						</tbody>
                					</table>
                				</div>
                			</li>";
                ?>
            </center>
        </div>
    </div>
</body>

<!--==============================footer=================================-->

<?php
include('../include/footer.php');
?>