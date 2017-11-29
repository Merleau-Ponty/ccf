<?php
session_start();
include ('../include/BDD.php');
$connexion = new BDD('ccf');
// PHP pour l'Ajax 

include("../include/header.php");

$tabcourse = $_SESSION['prof'];
$nb = count($tabcourse); // va permettre de rester sur la même ligne lors de l'insertion dans le tableau tabcourse

if (isset($_POST['numdos']) && isset($_POST['dons']) && isset($_POST['KM'])) {
    $tabcourse[$nb]['KM'] = $_POST['KM'];
    $tabcourse[$nb]['dons'] = $_POST['dons'];
    $tabcourse[$nb]['numdos'] = $_POST['numdos'];
    $tabcourse[$nb]['total'] = $_POST['KM'] * $_POST['dons'];

    $_SESSION['login'] = $tabcourse;
}

$DOSSARD = (isset($_POST['numdos'])) ? $_POST['numdos'] : '';
?>

<body>
    <div class="main">
        <header>
        </header>
        <?php
        //date_default_timezone_set('Europe/Paris');
        $_SESSION['current_date'] = date('d/m/Y');
        $_SESSION['ccf'] = file_get_contents('../include/date_ccf.dat');
        ?>
        <!--==============================Contenu================================-->
        <?php include("../include/nav.php") ?>

        <div class='cycle'>
            <center>
                <h2>Résultats de la course</h2>
            </center>

            <!------------------ FORMULAIRE ----------------------------------------->

            <form action="resultat.php" method="post" id='form'>
                <center>
                    <div class="input-field col s6">
                        <input type="text" name="numdos" id="numdos" />
                        <label for="numdos">Entrez le numéro de dossard</label>
                    </div>

                    <div id="participant"></div>
                </center>


                <!------------------------- RECUPERATION DONS ET KM ------------------------>
                <center>
                    <?php
                    if (isset($_POST['numdos']) && isset($_POST['dons']) && isset($_POST['KM'])) {
                        //$DOSSARD=$_SESSION['numdos1'];
                        //echo "2 $DOSSARD";
                        $DONS = $_POST['dons'];
                        $KM = $_POST['KM'];
                        if ($DOSSARD < 1) {
                            echo '<strong>Vous devez saisir un dossard</strong><br/>';
                        } else {
                            $requete2 = "UPDATE PARTICIPANTS SET KMPARCOURUS='$KM',PROMESSE='$DONS',MONTANT='$KM'*'$DONS' where DOSSARD='$DOSSARD'";
                        }
                        //echo $requete2;

                        try {
                            $resultats = $connexion->insert($requete2);
                        }
                        //si probleme sur l'execution de la requête
                        catch (PDOException $e) {
                            echo "Probleme pour lister les participants ";
                            $erreur = $e->getCode();
                            $message = $e->getMessage();
                            echo "erreur $erreur $message\n";
                            return;
                        }
                    }
                    ?>

                    <div class="input-field col s6">
                        <!-- Premier input -->
                        <input type="text" name="dons" id="dons" />
                        <label for="dons">Entrez la promesse de dons</label>

                    </div>

                    <div class="input-field col s6">
                        <!-- Deuxième input -->
                        <input type="text" name="KM" id="KM" />
                        <label for="KM">Entrez le nombre de KM</label>
                    </div>
                    <br/>
                    <button type="submit" class="waves-effect waves-light btn">OK</button>

                    <div id="tableau">
                        <!-------------------- AFFICHAGE DU TABLEAU DES RESULTATS ENTREES -------------------->
<?php
$var = '';
if (isset($_SESSION['prof'])) {
    $var .= "<table>";
    $var .= "<tr>";
    $var .= "<th>Numéro de dossard</th>";
    $var .= "<th>Dons</th>";
    $var .= "<th>Kilomètres</th>";
    $var .= "<th>Total</th>";
    $var .= "</tr>";
    if (count($_SESSION['login']) < 10) {
        $count = 0;
    } else {
        $count = count($_SESSION['login']) - 10;
    }
    // permet d'afficher le dernier résultat en haut du tableau

    for ($i = count($_SESSION['login']) - 1; $i > $count; $i--) {

        $var .= "<tr>";
        $var .= "<td>" . $_SESSION['login'][$i]['numdos'] . "</td>";
        $var .= "<td>" . $_SESSION['login'][$i]['dons'] . "</td>";
        $var .= "<td>" . $_SESSION['login'][$i]['KM'] . "</td>";
        $var .= "<td>" . $_SESSION['login'][$i]['total'] . "</td>";
        $var .= "</tr>";
    }
    $var .= "</table>";
    echo $var;
}
?>

                    </div>

                    <!-------------------- FIN AFFICHAGE DU TABLEAU DES RESULTATS ENTREES -------------------->

            </form>
            <br/>
            <br/>
            <form action="tabresultat.php" method="post">
                <button class="waves-effect waves-light btn">Consulter la liste des résultats</button>
            </form>



            </section>

            </center>

        </div>
    </div>
<?php include("../include/footer.php") ?>

    <script>
        $(document).ready(function ()
        {
            // CSS
            $('div.input-field.col.s6').width('230px');

            // Empêche la validation du formulaire si les valeurs sont incorrectes
            $('#form').submit(function (e)
            {
                if ($('#dons').val() == '' || $('#KM').val() == '' || $('#numdos').val() == '')
                {
                    e.preventDefault();
                    Materialize.toast('Veuillez remplir tous les champs', 3000);
                } else if (isNaN($('#dons').val()) || isNaN($('#KM').val()) || isNaN($('#numdos').val()))
                {
                    e.preventDefault();
                    Materialize.toast('Saisie incorrecte', 3000);
                }
            });

            ////////////////////// TRAITEMENT POUR LA RECUPERATION DU NUMERO DE DOSSARD /////////////////////////

            var test = "";
            // var dossard = document.getElementById('numdos').value;

            $("#numdos").change(function ()
            {
                var dossard = $("#numdos").val(); // récupération de la valeur du num dossard


                // instanciation de l'objet xhr via $.ajax
                $.ajax({

                    url: 'donnees_dossard.php', // script à exécuter
                    type: 'POST', // type de requête 
                    data: 'numdos=' + dossard, // passage de la valeur du dossard 

                    success: function (donnees) {
                        // vérification des données entrées (valeur valide et valeur numérique)
                        if (donnees == "[]" /*&& isNaN(donnees)*/)
                        {
                            $("#participant").html("<center><h3>Aucun participant correspondant</h3></center>");
                        } else
                        {
                            var donneesJSON = $.parseJSON(donnees); // convertir la chaine en objet JS
                            for (var i = 0; i < donneesJSON.length; i++)
                            {
                                test = "Elève : ";
                                test += donneesJSON[i].NOM + " " + donneesJSON[i].PRENOM + "&emsp;&emsp;Classe : " + donneesJSON[i].ID_CLASSE;
                                test += "<br>";
                            }
                            //                                   $("#participant").empty(); // vide le conteneurparticipant                
                            $("#participant").empty();
                            $("#participant").html("<center><h3>" + test + "</h3></center>"); // ajout du contenu à la div

                        }
                    },

                    error: function () {
                        $("#participant").html("<center><h3>Une erreur est survenue. Valeur incorrecte</h3></center>");
                    },

                });
            });


//                    // Gestion des parties de l'application
//                    eval(sessionStorage.ccf);
//                    eval(sessionStorage.courante);
//                    if(courante < ccf)
//                    {
//                        $('div.cycle').html('<h1 class=\'centrer\'>Résultats non saisissables</h1><p class=\'centrer\'>Nous sommes avant le jour de la course</p>');
//                    }
        });
    </script>