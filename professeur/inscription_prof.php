<?php
if (!isset($_SESSION['login'])) {
    session_start();
}
include '../include/BDD.php';
$connexion = new BDD('ccf');
include '../include/header.php';
$message = "";

if (isset($_POST['nom']) && isset($_POST['prenom'])) {
    //// permet de remettre l'auto increment à 1
    $requete = "ALTER TABLE PARTICIPANTS AUTO_INCREMENT = 1";
    $resultat = $connexion->insert($requete);

    $nom = trim(strtoupper($_POST['nom']));
    $prenom = trim(strtoupper($_POST['prenom']));

    //                var_dump($nom);
    //                var_dump($prenom);

    $requete = "select count(*) as nb from PARTICIPANTS where TRIM(NOM)='$nom' and TRIM(PRENOM)='$prenom';";
    //var_dump($requete);
    try {
        $resultats = $connexion->select($requete);
    }
    //si probleme sur l'execution de la requête
    catch (PDOException $e) {
        echo "Probleme pour acceder à la table participants - abandon ";
        $erreur = $e->getCode();
        $message = $e->getMessage();
        echo "erreur $erreur $message\n";
        return;
    }

    //              $tab = $resultats->FetchALL(PDO::FETCH_ASSOC);

    foreach ($resultats as $nombre) {
        $nb = $nombre['nb'];
        //                    var_dump($nb);
    }

    //                $nb=$resultats[$'nb'];
    if ($nb == 1) {
        $message = "<center><FONT COLOR=#ff0000>Participants déjà inscrit. Veuillez inscrire un autre participants</FONT></center>";
    } else {
        $req = "INSERT INTO `PARTICIPANTS`(`ID_CLASSE`,`NOM`, `PRENOM`,`INSCRIT`, `AUTORISATION`, `DROIT_IMAGE`,`DOSSIER`) VALUES ('PROF', '$nom', '$prenom','O','O','O','O')";

        try {
            $res = $connexion->insert($req);
            $message = "<center>Inscriptions prise en compte</center>";
        } catch (PDOException $e) {
            echo "Probleme pour acceder à la table eleve - abandon ";
            $erreur = $e->getCode();
            $message = $e->getMessage();
            echo "erreur $erreur $message\n";
            return;
        }
    }
}
?>

<body>
    <div class="main">
        <!--==============================Contenu================================-->
<?php include("../include/nav.php"); ?>

        <!--=========================Authentification============================-->
        <center>
            <h2>Inscription professeurs</h2></center>
        <div class="row">
            <form method="post" action="inscription_prof.php?login=<?= $_SESSION['login'] ?>" name="loginform" id="loginform" class="col s12">

                <div class="row">
                    <div class="input-field col s6">
                        <input type="text" id="nom" name="nom" />
                        <label for="nom">Nom </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input type="text" id="prenom" name="prenom" />
                        <label for="prenom">Prénom</label>
                    </div>
                </div>


                <center>
                    <button type="submit" name="Inscription" class="waves-effect waves-light btn">S'inscrire</button>
                </center>
            </form>
        </div>

<?php echo($message); ?>

    </div>
</section>

<!--==============================footer=================================-->
<?php include('../include/footer.php') ?>

<script>
    $(document).ready(function ()
    {
        $('#loginform').submit(function (e) {
            if ($('#nom').val() == '' || $('#prenom').val() == '') {
                e.preventDefault();
                Materialize.toast('Veuillez remplir tous les champs', 3000);
            }

        });

        var login = '<?= $_SESSION['login'] ?>';
        $.ajax({

            url: 'search_prof.php', // script à exécuter
            type: 'POST', // type de requête 
            data: 'login=' + login, // passage de la valeur du dossard 

            success: function (donnees) {
                // vérification des données entrées (valeur valide et valeur numérique)
                if (donnees == "[]" /*&& isNaN(donnees)*/) {
                    $("#loginform").html("<center><h3>Aucun professeur correspondant</h3></center>");
                } else {

                    var donneesJSON = $.parseJSON(donnees); // convertir la chaine en objet JS

                    $("#loginform input[type='text']").each(function () {
                        $(this).focus();
                        for (var i = 0; i < donneesJSON.length; i++) {


                            $("#nom").prop("value", donneesJSON[i].NOM_PROFESSEUR);


                            $("#prenom").prop("value", donneesJSON[i].PRENOM_PROFESSEUR);

                        }

                    });
                }
            },

            error: function ()
            {
                $("#loginform").html("<center><h3>Une erreur est survenue. Valeur incorrecte</h3></center>");
            },

        });

        // CSS
        $('label').css('margin-left', '12px');
        $('form > div').width('450px');
    });
</script>