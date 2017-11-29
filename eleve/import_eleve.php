<?php
#================== Header et Connection base de données ==================
include("../include/BDD.php");
include("../include/header.php");
$connexion = new BDD('ccf');

// Initialisation des variable
$msg = "";
$code_html = "";

#================== Si le formulaire est envoyé ==================
if (isset($_POST['fichier'])) {

    // On utilise un try catch pour récuperer les erreures
    try {

        // Initialisation des varibale
        $req = "";

        #================== Si le formulaire est envoyé est qu'un fichier est renseigné ==================
        if (isset($_POST['fichier']) && !empty($_POST['fichier'])) {

            // Initialisation des variable
            $code_html = "";
            // Récupération du fichier à importer
            // Le ficier dit être dans le même dossier que ce script
            $fichier = $_POST['fichier'];

            // Ouverture en lecture seule du fichier
            $handle = fopen($fichier, "r");

            // Affichage des élève inscrit
            $code_html = '
            		<div class="info-eleve card-panel green" id="info-bulle">
            			Vos élèves ont bien été inscrits
            		</div>
						<a href="#bas"> Bas de Page </a>
						<table class="table">
							<tr class="first_row">
								<td>
									<b>Classe</b>
								</td>
								<td>
									<b>Nom</b>
								</td>
								<td>
									<b>Prenom</b>
								</td>
								<td>
									<b>Login</b>
								</td>
								<td>
									<b>Mot de passe</b>
								</td>
							</tr>';

            // Boucle pour récuperer les données des élèves un par un
            while (($data = fgetcsv($handle, 4000, ";")) !== FALSE) {
                $classe = $data[0];
                $nom = $data[1];
                $prenom = $data[2];
                $login = $data[3];
                $mdp = $data[4];

                // Séquence de cryptage pour le mot de passe
                if (!defined('SALT'))
                    define("SALT", "<b}DKJ{]1QIcW<-`Kn|ENm(w1l9>epVQvkhyEaN*dfJEA{MZ");
                // On hash le mot de passe de façon a ce qu'il soit non décryptable
                $pass = sha1(SALT . md5($mdp . SALT) . sha1(SALT));

                // Requête d'nsertion des élèves dans la base
                $req = "INSERT INTO `PARTICIPANTS`(`ID_CLASSE`, `NOM`, `PRENOM`, `LOGIN_ELEVE`,`MDP_ELEVE`) VALUES ('$classe','$nom','$prenom','$login','$pass');\n";

                // Execution de la requête
                $res = $connexion->insert($req);

                // Affichage des élève inscrit SUITE
                $code_html .= "
    						<tr>
    							<td>
    								$classe
    							</td>
    							<td>
    								$nom
    							</td>
    							<td>
    								$prenom
    							</td>
    							<td>
    								$login
    							</td>
    							<td>
    								$pass
    							</td>
    						</tr>";
            }

            // Fermeture du fichier
            fclose($handle);
            $code_html .= '
            			</table>
                            <form method="post" action="../index.php" class="col s12">
                                <div class="row">
                                    <br/>
                                    <input type="text" name="requete" style="display:none;" value="' . $req . '"/>
                                    <input type="submit" value="Envoyer" class="btn" />
                                </div>
                            </form>
                            <p id="bas" name="bas" hidden=""></p>';
        } else {
            // Message si on ne choisit pas de fichier
            $msg = "<br>Veuillez choisir un fichier";
        }
    } catch (Exception $e) {
        // Affichage de l'erreur
        echo $e->getMessage();
    }
}
?>


<!-- =================== Contenu de la page =================== -->
<body>
    <div class="main">
        <!-- =================== Menu =================== -->
        <?php include '../include/nav.php' ?>
        <center>
            <div class="cycle">
                <div>

                    <center>
                        <h2>Importation Élèves</h2>
                    </center><br/>

                    <form method='post' action='import_eleve.php' class='col s12'>
                        <div clas='row'>

                            <!-- =================== Pour aller chercer le fichier =================== -->
                            <div class='file-field input-field'>
                                <div class='btn'>
                                    <span>File</span>
                                    <input type='file' name='fichier' accept='.csv' />
                                </div>
                                <div class='file-path-wrapper'>
                                    <input class='file-path validate' type='text' placeholder='Choisir un fichier'>
                                </div>
                            </div>

                            <!-- =================== Envoyer le fichier  =================== -->
                            <div>
                                <input type='submit' value='Valider' class='btn'">
                            </div>

                            <!-- =================== Message si on en choisit pas de fichier =================== -->
                            <div>
                                <?= $msg ?>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- =================== Affichage des élève importer =================== -->
                <div>
                    <br>
                    <?= $code_html ?>
                </div>
            </div>   
        </center>
    </div>

    <!-- =================== Footer =================== -->
    <?php include('../include/footer.php') ?>