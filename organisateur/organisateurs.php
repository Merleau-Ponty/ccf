<?php
session_start();

// Initialisation des variables
$message = '';

#================== Si le formulaire à été envoyé ==================
if (isset($_POST["username"])) {
    include '../include/BDD.php';
    include 'verification_organisateur.php';
}

#================== Header ==================
include("../include/header.php");
?>

<body>
    <div class="main">
        <!-- =================== Menu =================== -->
        <?php include("../include/nav.php") ?>

        <!-- =================== Authentitification =================== -->
        <center><h2>Authentification</h2></center>
        <div class="row">
            <form method="post" action="organisateurs.php" name="loginform" id="loginform" class="col s12">

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

        <div>
            <?= $message ?>
        </div>
    </div>

    <!-- ============================== Footer ================================= -->
    <?php include("../include/footer.php") ?>

    <script type='text/javascript'>
        $(document).ready(function () {

            // CSS
            $('label').css('margin-left', '12px');
            $('form > div').width('450px');
        });
    </script>
