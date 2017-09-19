<?php
include("../include/BDD.php");
include("../include/header.php");
$connexion = new BDD('ccf');
?>
    </head>

    <body>
        <div class="main">
            <header>

            </header>
            <!--==============================Contenu================================-->
            <?php include("../include/nav.php") ?>

                <center>
                    <div class="cycle">
                        <div>
                            <center>
                                <h2>Importation Professeurs</h2></center>
                            <br>
                            <?php
// si aucun fichier n'est récupéré
if(!isset($_POST['fichier'])){
    //permet de choisir dans l'aborescence le fichier à importer
    echo"

<form method='post' action='import_prof.php' class='col s12'>
<div clas='row'>
<div class='file-field input-field'>
      <div class='btn'>
        <span>File</span>
        <input type='file' name='fichier' accept='.csv'/>
      </div>
      <div class='file-path-wrapper'>
        <input class='file-path validate' type='text' placeholder='Choisir un fichier'>
      </div>
</div>
</div>
<input type='submit' value='Valider' class='btn'>
</form>";
}

/// si on récupère un nom de fichier
if(isset($_POST['fichier'])){
    // récupération du fichier à importer
    $fichier = $_POST['fichier'];

    $fp= fopen($fichier,"r"); // ouverture en lecture seule
   

// récupération du contenu et insertion de la base
    // Tant que le fichier n'est pas totalement lu
    while(!feof($fp)){
            $ligne = fgets($fp,4096); // récupére chaque ligne

            // récupération des champs séparés par ; dans liste
            $liste = explode(";",$ligne);

            // assignation aux variables correspondantes
        
            // utilisation de la fonction trim pour enlever les espaces (évite les caractères vides dans les champs)
            $login= trim($liste[0]);
            $mdp= trim($liste[1]);
            $nom=trim($liste[2]);
            $prenom=trim($liste[3]);


            // insertion dans la base
            $req="INSERT INTO `PROFESSEURS`(`LOGIN`, `MOT_DE_PASSE`, `NOM_PROFESSEUR`, `PRENOM_PROFESSEUR`) VALUES ('$login','$mdp','$nom','$prenom')";
            $res=$connexion->insert($req);
    }
           /* // vérification erreur
            if(!$res){
                print_r($connexion->errorInfo());
        }*/
        echo "<center><h3>Importation terminée avec succès.</h3></center>";
    print_r($res);
        // fermeture fichier
        fclose($fp);
}
?>
                        </div>
                </center>
                </div>
                <!--==============================footer=================================-->
                <?php include("../include/footer.php") ?>