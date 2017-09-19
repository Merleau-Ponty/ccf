<?php
include_once "connexion.php";
global $connexion;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Ordre de passage</title>
    <meta charset="utf-8">
    <!--<link rel="stylesheet" type="text/css" media="screen" href="css/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/grid.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/cycle.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/table.css">-->
    <link href='http://fonts.googleapis.com/css?family=Passion+One:400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800' rel='stylesheet' type='text/css'>
    <script src="js/jquery-1.7.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.cycle.all.js"></script>
    <script>
		jQuery(document).ready(function() {
			$('#s2').cycle({ 
				fx:     'fade', 
				speed:  'slow', 
				pager:  '#nav',
				timeout: 8000, 
			});
		});
	</script>
</head>
<body>

<?php
$requete = "select NOM,PRENOM,VESTIAIRE,REMISE,DEPART,FIN,ELEVE.IDCLASSE,DOSSARD from ELEVE 
			inner join CLASSE on ELEVE.IDCLASSE=CLASSE.IDCLASSE 
			inner join COURSES on CLASSE.COURSE=COURSES.NO_COURSE 
			where INSCRIT='O' order by NOM";
					try {
					$resultats = $connexion->query($requete);
					}
					//si probleme sur l'execution de la requête
					catch (PDOException $e) {
					echo "Probleme pour lister les eleves - abandon ";
					$erreur = $e->getCode();
					$message = $e->getMessage();
					echo "erreur $erreur $message\n";
					return;
					}
					// on dit qu'on veut que le résultat soit récupérable sous forme de tableau)
					$tableau=$resultats->fetchAll(PDO::FETCH_ASSOC);
					$i=0;
					foreach ($tableau as $tab_eleve) {
						
							echo utf8_decode("<div style=\"page-break-after:always; text-align:justify;\"><h1>À l&acute;attention des participants à la Course Contre la Faim du vendredi 16 mai 2014</h1><br/>");
							echo utf8_decode("<strong>".$tab_eleve['PRENOM']." ".$tab_eleve['NOM']." ".$tab_eleve['IDCLASSE']." Dossard : ".$tab_eleve['DOSSARD']."</strong>");
							echo "<br/>";
							echo utf8_decode("<p>Le Comité d&acute;Organisation du lycée ainsi que les membres de l&acute;association ACF vous remercient chaleureusement pour votre engagement dans la belle aventure de la Course Contre la Faim.<br/>

							Vous allez participer à la Course Contre la Faim qui est un événement international.<br/>
							En donnant ces quelques heures (course et recherche de vos parrains), vous faites preuve d&acute;une grande générosité : vous aidez des populations démunies et affaiblies par les guerres, la pauvreté, les catastrophes naturelles...<br/>
							1 &euro; + 1 &euro;... vont pouvoir sauver des familles et des vies humaines.<br/>

							Nous souhaitons que cette journée soit la plus festive possible, c&acute;est pourquoi vous pouvez venir déguisés si vous le désirez. Bien sûr, ceux qui ne courent pas sont invités à venir encourager leurs camarades qui seront sur la piste d&acute;athlétisme.<br/></p>");
							echo utf8_decode("Merci de respecter les horaires suivants :<br/>");
							echo utf8_decode("<strong>".substr($tab_eleve['VESTIAIRE'],-8,5)."</strong> : vous vous préparez dans les vestiaires<br/>");
							echo utf8_decode("<strong>".substr($tab_eleve['REMISE'],-8,5)."</strong> : vous récupérez votre dossard<br/>");
							echo utf8_decode("<strong>".substr($tab_eleve['DEPART'],-8,5)."</strong> : débutez votre course<br/>");
							echo utf8_decode("<strong>".substr($tab_eleve['FIN'],-8,5)."</strong> : course terminée, vous vous présentez au stand \"Secrétariat\" afin de valider les km effectués, puis vous allez aux vestiaires. Retour en cours.<br/>");
							$i=$i+1;
							if($i%2==0){
								echo "</div>";
							}
					}
					
?>
<script type="text/javascript">
function edition()
    {
    options = "Width=700,Height=700" ;
    window.open( "edition.php", "edition", options ) ;
    }
window.print() ;
</script>
</body>
</html>