<?php session_start();
include("../include/header.php") ?>

<body>
    <div class="main">
        <!-- ============================== Contenu ================================ -->
        <?php include("../include/nav.php") ?>

        <div class="centrer">
            <div>
                <h2 class="centrer">Bienvenue</h2>
                <br/>
                <?php
                if ($_SESSION['ccf_amj'] <= $_SESSION['current_amj']) {

                    echo"<h2 class=\'centrer\'>Les inscriptions sont terminées</h2>";
                } else {
                    //avant la course
                    echo "<a href='inscription_eleve.php'><button class='waves-effect waves-light btn' >S'inscrire à la course</button></a>";
                }
                ?>

            </div>
            <br/>
            <div>
                <a href='autorisationparents.pdf'><button name="telechargement" class="waves-effect waves-light btn">Téléchargement autorisation parentale</button></a> 
            </div>
        </div>
    </div>

    <!-- ============================== Footer ================================= -->
<?php include("../include/footer.php") ?>

    <script type='text/javascript'>
        // Gestion des parties de l'application - espace élève
        var d = new Date();
        courante = new Date(d.getFullYear(), d.getMonth(), d.getDate());
        eval(sessionStorage.ccf);
        eval(sessionStorage.courante);
        console.log('courante : ' + courante);
        console.log('ccf : ' + ccf);
        if (courante > ccf)
        {
            //$('div.centrer').html('<h2 class=\'centrer\'>Les inscriptions sont terminées</p>');
            $('div.centrer').html('<h2 class=\'centrer\'>La course est finie</h2>\n<p class=\'centrer\'>Vous pouvez consulter les résultats, s\'ils sont disponibles</p>');
        }


    </script>
