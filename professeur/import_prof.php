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
                        Les professeurs ont bien été inscrits.
                    </div>
                        <a href="#bas"> Bas de Page </a>
                        <table class="table">
                            <tr class="first_row">
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
                $login = mb_strtolower($data[2]);
                $mdp = trim($data[3]);
                $nom = trim($data[0]);
                $prenom = trim($data[1]);

                // Séquence de cryptage pour le mot de passe
                if (!defined('SALT'))
                    define("SALT", "<b}DKJ{]1QIcW<-`Kn|ENm(w1l9>epVQvkhyEaN*dfJEA{MZ");
                // On hash le mot de passe de façon a ce qu'il soit non décryptable
                $pass = sha1(SALT . md5($mdp . SALT) . sha1(SALT));

                // Requête d'nsertion des élèves dans la base
                $req = "INSERT INTO `PROFESSEURS`(`LOGIN`, `MOT_DE_PASSE`, `NOM_PROFESSEUR`, `PRENOM_PROFESSEUR`) VALUES ('$login','$pass','$nom','$prenom')";

                // Execution de la requête
                $res = $connexion->insert($req);

                // Affichage des élève inscrit SUITE
                $code_html .= "
                            <tr>
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
                        <h2>Importation Professeurs</h2>
                    </center><br/>

                    <form method='post' action='import_prof.php' class='col s12'>
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