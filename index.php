<?php
session_start();

// PHP pour l'Ajax 
if (isset($_POST['ajax'])) {
    $json = array("date_courante" => $_SESSION['current_date'], "date_ccf" => $_SESSION['ccf']);
    echo json_encode($json);
    exit;
}

//Gestion de la date pour les différentes parties de l'application
$_SESSION['current_date'] = date('d/m/Y');
$_SESSION['ccf'] = file_get_contents('include/date_ccf.dat');
$dateccf = DateTime::createFromFormat('d/m/Y', $_SESSION['ccf']);
$datejour = DateTime::createFromFormat('d/m/Y', $_SESSION['current_date']);

// Si la date respecte le format la méthode createFromFormat renvoie un objet de type DateTime et peut disposer //de ses méthodes : la méthode format renvoie la date sous un format différent
$_SESSION['ccf_amj'] = $dateccf->format('Y-m-d');
$_SESSION['current_amj'] = $datejour->format('Y-m-d');
?>


<!DOCTYPE html>
<html lang="fr">
    <!-- =================== Header =================== -->
    <head>
        <meta content="text/html" charset="utf-8" http-equiv="Content-type" />
        <title>Accueil</title>
        <!-- CSS -->
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection" />
        <link type="text/css" rel="stylesheet"  href="css/ccf.css" />

        <!-- Pour la version mobile -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!-- Importation du JS -->
        <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </head>

    <!-- =================== Contenu =================== -->
    <body>

        <!-- =================== Menu =================== -->
        <section id="content">
            <div class="navbar-fixed">
                <nav>
                    <div class="nav-wrapper cyan darken-3">
                        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                        <a href="#" class="brand-logo right"><img src="./images/logo-ccf.png"></a>

                        <!-- Menu desktop -->
                        <ul class="left hide-on-med-and-down">
                            <li><a href="./index.php"><i class="material-icons">home</i></a></li>
                            <li><a href="./eleve/eleves.php">Espace élèves</a></li>
                            <li><a href="./professeur/professeurs.php">Espace professeurs</a></li>
                            <li><a href="./organisateur/organisateurs.php">Espace organisateurs</a></li>
                            <li><a href="./resultat/espaceresultat.php">Résultats</a></li>
                            <li><a href="./professeur/inscriptions_professeurs.php">Inscription Professeurs</a></li>
                        </ul>

                        <!-- Menu mobile -->
                        <ul class="side-nav" id="mobile-demo">
                            <li><a href="./index.php">Accueil</a></li>
                            <li><a href="./eleve/eleves.php">Espace élèves</a></li>
                            <li><a href="./professeur/professeurs.php">Espace professeurs</a></li>
                            <li><a href="./organisateur/organisateurs.php">Espace organisateurs</a></li>
                            <li><a href="./resultat/espaceresultat.php">Résultats</a></li>
                            <li><a href="./professeur/inscriptions_professeurs.php">Inscription Professeurs</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </section>

        <!-- =================== Etat de la course =================== -->
        <div class="col s12 m2 info-ccf z-depth-2 card-panel green" id="info-bulle">
            C'est le jour de la course, n'oubliez pas !
        </div>

        <!-- =================== Bouton pour aller au resultat =================== -->
        <form action="resultat/tabresultat.php" id="boutonResultats">
            <center>
                <button class="waves-effect waves-light btn" id='liste-res'>Liste des résultats</button>
            </center>
            <br>
        </form>

        <!-- =================== Slider =================== -->
        <div class="slider">
            <ul class="slides">
                <li>
                    <img src="images/slider-2.jpg">
                    <div class="caption left-align">
                        <h3>Action contre la faim</h3>
                        <h5 class="light grey-text text-lighten-3">Une course humanitaire</h5>
                    </div>
                </li>
            </ul>
        </div><br/>

        <!-- =================== Texte d'explication =================== -->
        <div class="container_24 top-1">
            <center><h2>A vos marques, prêts ? Participez !</h2></center>
            <p align="justify" class="p-index">Tu veux te mobiliser avec le lycée Merleau Ponty contre la faim dans le monde ? <br>
                L'année dernière, ce sont près de 265 000 élèves qui se sont mobilisés en France et dans le monde contre le fléau de la faim, et la région Poitou-Charentes a été celle qui a récolté le plus de dons.
                <br>Pour rejoindre ce mouvement de solidarité, il te suffit de t'inscrire sur ce site et de participer à la Course contre la Faim.
                <br><span class="gras">Comment ça marche ?</span>
                <br><span class="gras">- Avant la course</span>
                Un intervenant d'Action contre la Faim vient sensibiliser les élèves du lycée Merleau Ponty au fléau de la faim dans le monde. <br>&emsp;&emsp;Après la séance, munis du passeport que vous remettra votre professeur référent, vous partez à la recherche de sponsors qui vont vous parrainer pour chaque kilomètre que vous courrez le jour de la course.
                <br><span class="gras">- Le jour J</span>
                <br>&emsp;&emsp;Vous prenez le départ de la Course contre la Faim organisée par le lycée Merleau Ponty, dans une ambiance festive et solidaire.  <br>&emsp;&emsp;Il n'y a pas de compétition, il s'agit d'une course d'endurance où chacun court à son rythme.
                <br><span class="gras">- Après la course</span>
                <br>&emsp;&emsp;Chaque élève récolte une somme auprès de ses parrains en fonction des kilomètres parcourus.
                <br><br><span class="centrer">Si, toi aussi, tu veux agir à ton niveau contre la faim dans le monde, inscris-toi dès maintenant à la Course contre la Faim.
                    <br/>On compte sur toi !</span></p>
            <br/>
        </div> 


        <script type='text/javascript'>
            $(document).ready(function () {
                // Requête Ajax pour obtenir la date actuelle du serveur, et celle de la CCF
                var ajax = true;
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: 'ajax=' + ajax,
                    dataType: 'json',
                    success: function (res) {
                        gestionDates(res);
                    },
                    error: function () {
                        alert("Erreur Ajax");
                    }
                })
            });

            function gestionDates(res) {
                var d_ccf = res.date_ccf.split('/');
                var d_courante = res.date_courante.split('/');
                // Enregistrement des dates en variables de session JS
                sessionStorage.ccf = 'var ccf = new Date(' + d_ccf[2] + ',' + (+d_ccf[1] - 1) + ',' + d_ccf[0] + ');';
                sessionStorage.courante = 'var courante = new Date(' + d_courante[2] + ',' + (+d_courante[1] - 1) + ',' + d_courante[0] + ');';
                // Evaluation des chaînes de caractères en code pour être réutilisables
                eval(sessionStorage.ccf);
                eval(sessionStorage.courante);
                // Modification de la bulle d'information selon la date
                if (courante < ccf) {
                    $('#info-bulle').html('La course n\'est pas commencée, les inscriptions sont disponibles');
                    $('#liste-res').hide();
                } else if (courante > ccf) {
                    $('#info-bulle').html('La course est finie, vous pouvez consultez les résultats');
                } else {
                    $('#liste-res').hide();
                }
            }
            ;
        </script>

        <!-- =================== Footer =================== -->
        <?php include "include/footer.php" ?>


