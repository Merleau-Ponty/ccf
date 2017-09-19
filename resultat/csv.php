<?php
include ("../include/BDD.php");
//accès à la la base de données
//modif git2
//function vers_csv($requete){
$connexion=new BDD('ccf');
$num_req=$_GET["num_req"];
if($num_req==1){
//Premiere ligne = nom des champs (
	$xls_output = "Nom;Prenom;Classe;Dossard;Distance;Promesse;Montant";
//Requete SQL
	$query='SELECT NOM,PRENOM,ID_CLASSE,DOSSARD,KMPARCOURUS,PROMESSE,MONTANT FROM PARTICIPANTS where KMPARCOURUS<>0 order by ID_CLASSE , NOM';
}
if($num_req==2){
	$login=$_GET["LOGIN"];
	$xls_output ="Nom;Prénom;Classe;Promesse de dons;Montant réel&nbsp;";
	$requete = "select NO_PARTICIPANT,PARTICIPANTS.NOM,PARTICIPANTS.PRENOM,PARTICIPANTS.ID_CLASSE,INSCRIT,AUTORISATION,MONTANT,MTREEL from PARTICIPANTS
	inner join CLASSE on PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
	inner join PROFESSEURS on PROFESSEURS.LOGIN = CLASSE.LOGIN
	where PROFESSEURS.LOGIN='$login' and INSCRIT='O' AND AUTORISATION='O'
	order by PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";
}
$xls_output .= "\n";





$resultats=$connexion->select($query);
  // on dit qu'on veut que le résultat soit récupérable sous forme de tableau)
//$tab=$resultats->fetchALL(PDO::FETCH_ASSOC);

  //Boucle sur les resultats
foreach ($resultats as $ligne){
	foreach ($ligne as $elt ) {
		$xls_output .= "$elt;";
	}
	$xls_output .="\n";

}


header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=ccf_" . date("Ymd").".csv");
print $xls_output;
return;
//}
?>