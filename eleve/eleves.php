<?php
session_start();

// Initialisation des variables
$message = "";

#================== Header et Connection base de données ==================
include("../include/header.php");

#================== Si le formulaire à été envoyé ==================
if (isset($_POST["username"])) {
    $_SESSION['login'] = $_POST["username"];
    include ('../include/BDD.php');
    include('verification_elev.php');
}
?>


<body>
    <div class="main">
        <!-- =================== Menu =================== -->
        <?php include("../include/nav.php"); ?>

        <!-- =================== Authentitification =================== -->
        <center><h2>Authentification</h2></center>
        <div class="row">
            <form method="post" action="eleves.php" name="loginform" id="loginform" class="col s12">

                <!-- =================== Nom d'utilisateur et Mot de passe =================== -->    
                <div class="row">
                    <div class="input-field col s6">
                        <input type="text" value="" id="username" name="username">
                        <label for="username">Nom d'utilisateur</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 marge-input">
                        <input type="password" value="" id="password" name="password">
                        <label for="password">Mot de passe</label>
                    </div>
                </div>

                <!-- =================== Bouton connexion =================== -->
                <div class="row">
                    <center>
                        <button onclick="document.getElementById('loginform').submit()" name="Connexion" class="waves-effect waves-light btn">Connexion</button>
                    </center>
                </div>
            </form>
        </div>

        <!-- =================== Message en cas de mauvaise identification =================== -->
        <div>
            <?= $message ?>
        </div>
    </div>


    <!-- =================== Footer =================== -->
    <?php include('../include/footer.php') ?>


    <script type='text/javascript'>
        // Gestion des parties de l'application - espace élève
        eval(sessionStorage.ccf);
        eval(sessionStorage.courante);

        // Après la course
        if (courante > ccf) {
            $('h2').empty();
            $('div.row:first').html('<h2 class=\'centrer\'>La course est finie</h2>\n<p class=\'centrer\'>Vous pouvez consulter les résultats, s\'ils sont disponibles</p>');
        }
        // Jour de la course
        else if (courante.getTime() == ccf.getTime()) {
            $('h2').empty();
            $('div.row:first').html('<h2 class=\'centrer\'>La course est aujourd\'hui</h2>\n<p class=\'centrer\'>N\'oubliez pas d\'y participer si vous vous êtes inscrit</p>');
        }

        // CSS
        $('label').css('margin-left', '12px');
        $('form > div').width('450px');
    </script>