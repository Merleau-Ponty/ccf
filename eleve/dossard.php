<?php
include_once "../include/BDD.php";
$connexion=new BDD('ccf');
global $connexion;
?>
<!DOCTYPE html>

<?php
$requete = "select * from PARTICIPANTS
			where trim(INSCRIT)!='N' order by NOM,PRENOM";
					try {
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
//					$tableau=$resultats->fetchAll(PDO::FETCH_ASSOC);
					$cpt=1;
					$couleur='';
					foreach ($resultats as $tab_eleve) 
                    
                    {
						if($cpt<=100)
                        {
							$couleur='Rouge';
						}
						if($cpt>100 && $cpt<=200)
                        {
							$couleur='Vert';
						}
						if($cpt>200 && $cpt<=300)
                        {
							$couleur='Bleu';
						}
						if($cpt>300 && $cpt<=400)
                        {
							$couleur='Jaune';
						}
						if($cpt>400 && $cpt<=500)
                        {
							$couleur='Blanc';
						}
						if($cpt>500 && $cpt<=600)
                        {
							$couleur='Rouge';
						}
						if($cpt>600 && $cpt<=700)
                        {
							$couleur='Vert';
						}
						$no_eleve=$tab_eleve['NO_PARTICIPANT'];

						echo $tab_eleve['NO_PARTICIPANT'].' '.$tab_eleve['NOM']." ".$cpt." '".$couleur."'\n";

						$requete="update PARTICIPANTS set DOSSARD=$cpt, COULEUR='$couleur' where trim(NO_PARTICIPANT)=trim($no_eleve)";
						try
                        {
//						 $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$connexion->insert($requete);
						}
						catch (PDOException $e) 
                        {
							echo "Probleme pour updater les participants - abandon ";
							$erreur = $e->getCode();
							$message = $e->getMessage();
							echo "erreur $erreur $message\n";
							return;
					}
						$cpt++;
					}

?>
