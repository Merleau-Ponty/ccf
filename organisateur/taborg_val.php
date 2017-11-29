<?php
#================== Header et Connection base de données ==================
include("../include/header.php");
include_once "../include/BDD.php";
$connexion = new BDD('ccf');


#================== Si le login est dans l'URL (premier chargement de la page) ==================
if (isset($_GET['login'])) {
    $login = $_GET['login'];
}
#================== Il n'y a plus le login dans l'url alors on la mit dans un input pour le recuperer ================== 
else {
    $login = $_POST['login'];
}


#================== Récupération des checkboxes qui ont été cocher ==================
// Si le formulaire à été envoyer on recupere les checkboxes sinon c'est vide
$checkboxes = isset($_POST['inscription']) ? $_POST['inscription'] : array();

// Pour chaque checkboxes recupérée
foreach ($checkboxes as $value) {
    // Requête pour mettre a jour les informations de l'élève en t'en que validé
    $requete = "UPDATE PARTICIPANTS set INSCRIT = 'O', AUTORISATION = 'O', DROIT_IMAGE = 'O' where NO_PARTICIPANT = '" . $value . "'";

    // Execution de la requête
    $inscription = $connexion->insert($requete);
}


#================== Inscrit par classe ==================
// Requête pour recupérer les classe ou il y a des inscrit
$requete = "SELECT ID_CLASSE from PARTICIPANTS where INSCRIT='O' order by ID_CLASSE";

// Execution de la requête
$resultats = $connexion->select($requete);

// Affecttation de la classe dans une variable
$sauveclasse = $resultats[0]['ID_CLASSE'];


// Requête pour récuperer le nombre d'élève de la classe recuperer au dessus
$requete5 = "SELECT ID_CLASSE, count(NO_PARTICIPANT) AS nbeleves2 FROM PARTICIPANTS WHERE INSCRIT='O' AND ID_CLASSE='$sauveclasse' GROUP BY ID_CLASSE";

// Execution de la requête
$resultats5 = $connexion->select($requete5);

// Affection du nombre d'élève dans une variable
$nbeleves2 = $resultats5[0]['nbeleves2'];

// Affichage de la classe et du nombre délève
$table_nbparticipants = "
                    <tr>
                        <td>
                            <strong>
                                <center>
                                    $sauveclasse
                                </center>
                            </strong>
                        </td>
                        <td>
                            <strong>
                                <center>
                                    $nbeleves2
                                </center>
                            </strong>
                        </td>
                    </tr>";

// Pour chaque classe
foreach ($resultats as $tab_nbeleve) {
    // Si la classe est differente de la classe en cours
    if ($tab_nbeleve['ID_CLASSE'] <> $sauveclasse) {
        // Initialisation des variable
        $sauveclasse = $tab_nbeleve['ID_CLASSE'];

        // Requête pour récuperer le nombre d'élève de la classe recuperer au dessus
        $requete5 = "SELECT ID_CLASSE, count(NO_PARTICIPANT) AS nbeleves2 FROM PARTICIPANTS WHERE INSCRIT='O' AND ID_CLASSE='$sauveclasse' GROUP BY ID_CLASSE";

        // Execution de la requête
        $resultats5 = $connexion->select($requete5);

        // Affection du nombre d'élève dans une variable
        $nbeleves2 = $resultats5[0]['nbeleves2'];

        // Affichage de la classe et du nombre délève
        $table_nbparticipants .= "
                    <tr>
                        <td>
                            <strong>
                                <center>
                                    $sauveclasse
                                </center>
                            </strong>
                        </td>
                        <td>
                            <strong>
                                <center>
                                    $nbeleves2
                                </center>
                            </strong>
                        </td>
                    </tr>";
    }
}


#================== Inscrit et inscrit validé ==================
// Requête pour le nombre d'élève inscrits
$requete2 = "SELECT count(*) as nbeleves from PARTICIPANTS where INSCRIT='O' or INSCRIT='V';";

// Execution de la requête2
$resultats2 = $connexion->select($requete2);

// Affection du nombre d'élèves inscrit dans une variable
$nbeleves = $resultats2[0]['nbeleves'];


// Requête pour le nombre d'élève inscrit et validé
$requete2B = "SELECT count(*) as nbeleves from PARTICIPANTS where INSCRIT='O';";

// Affection du nombre d'élèves inscrit et validés dans une variable
$resultats2B = $connexion->select($requete2B);

// Affection du nombre d'élèves inscrit et validés dans une variable
$nbelevesV = $resultats2B[0]['nbeleves'];


#================== Tableau de validation des élèves ==================
// Input cacher pour garder le login de l'élève
$table_validation = "<input type='hidden' name='login' value=$login>";

// Si le formulaire à été envoyer on recupere les checkboxes sinon c'est vide
$checkboxes = isset($_POST['inscription']) ? $_POST['inscription'] : array();

// Initalisation des variables
$requete = '';
$requete2 = '';
/* $sauveclasse = ''; */

// Requête pour recuperer les informations des participants
$requete = "SELECT NO_PARTICIPANT,PARTICIPANTS.NOM,PARTICIPANTS.PRENOM,PARTICIPANTS.ID_CLASSE, PARTICIPANTS.INSCRIT,AUTORISATION,DROIT_IMAGE 
            from PARTICIPANTS
            inner join CLASSE on PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
            where INSCRIT IN ('O','V','N')
            order by PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";

// Execution de la requête
$resultats = $connexion->select($requete);

// Si le nombre de resultats et different de 0
if (count($resultats) != 0) {
    // Récupération de la première classe
    $sauveclasse = $resultats[0]['ID_CLASSE'];
    // Initialisation des variables
    $cpt = 0;

    // Affichage du tableau de validation des élèves
    $table_validation .= "<ul class='collapsible' data-collapsible='accordion'>
                                <li>
                                    <div class='collapsible-header centrer'>
                                        $sauveclasse
                                    </div>
                                    <div class='collapsible-body'>
                                        <table name='participants[]' class='centered'>
                                            <thead>
                                                <tr>
                                                    <th>Nom</th>
                                                    <th>Prénom</th>
                                                    <th>Classe</th>
                                                    <th>Inscription</th>
                                                    <th>Autorisation</th>
                                                    <th>Droit à l'image</th>
                                                   <th>Validation</th>
                                                </tr>
                                            </thead>
                                            <tbody>";

    // Pour chaque résultat de la requête
    foreach ($resultats as $tab_eleve) {
        // On a ajoute 1 au compteur à chaque tour
        $cpt += 1;

        // Si la classe est differente de la classe en cours
        if ($tab_eleve['ID_CLASSE'] <> $sauveclasse) {
            // Initialisation des variable
            $sauveclasse = $tab_eleve['ID_CLASSE'];

            // Affichage du tableau de validation des élèves
            $table_validation .= "
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                                <li>
                                    <div class='collapsible-header centrer'>
                                        $sauveclasse
                                    </div>
                                    <div class='collapsible-body'>
                                        <table name='participants[]' class='centered'>
                                            <thead>
                                                <tr>
                                                    <th>Nom</th>
                                                    <th>Prénom</th>
                                                    <th>Classe</th>
                                                    <th>Inscription </th>
                                                    <th>Autorisation</th>
                                                    <th>Droit à l'image</th>
                                                    <th>Validation</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
        }

        // Si l'élève est inscrit et déjà valider on coche la case et on la bloque
        $checked = ($tab_eleve['INSCRIT'] == 'O') ? "disabled checked" : "";

        // Si l'élève est inscrit et valide la couleur de fond de sa ligne est vert, si il est inscrit mais non validé le fond de sa ligne sera rouge sinon il restera blanc
        $color = ($tab_eleve['INSCRIT'] == 'O') ? "class='val'" : (($tab_eleve['INSCRIT'] == 'V') ? "class='non-val'" : "");

        // Affichage du tableau de validation des élèves
        $table_validation .= "
                                                <tr>
                                                    <td $color>" . $tab_eleve['NOM'] . "</td>
                                                    <td $color>" . $tab_eleve['PRENOM'] . "</td>
                                                    <td $color>" . $tab_eleve['ID_CLASSE'] . "</td>
                                                    <td $color>" . $tab_eleve['INSCRIT'] . "</td>
                                                    <td $color>" . $tab_eleve['AUTORISATION'] . "</td>
                                                    <td $color>" . $tab_eleve['DROIT_IMAGE'] . "</td>
                                                    <td $color><input type='checkbox' class='filled-in' id='filled-in-box" . $cpt . "' name='inscription[]' value=" . $tab_eleve["NO_PARTICIPANT"] . " style='visibility:hidden' " . $checked . "><label for='filled-in-box" . $cpt . "'></label></td>
                                                </tr>";
    }
    // Affichage du tableau de validation des élèves
    $table_validation .= "                      
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                            </ul>";
}







// Requête pour permettre de trié les classe avec le bouton trier la classe
$requete2 = "SELECT NO_PARTICIPANT,PARTICIPANTS.NOM,PARTICIPANTS.PRENOM,PARTICIPANTS.ID_CLASSE, PARTICIPANTS.INSCRIT,AUTORISATION,DROIT_IMAGE 
            from PARTICIPANTSCLASSE
            inner join CLASSE on PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
            inner join PROFESSEURS on CLASSE.LOGIN = PROFESSEURS.LOGIN
            where INSCRIT IN ('O','V','N')
            order by PARTICIPANTS.ID_CLASSE,
            case INSCRIT
            when 'O' then 1
            when 'V' then 2
            when 'N' then 3
            end,
            PARTICIPANTS.NOM";

// Execution de la requête
$resultats2 = $connexion->select($requete2);
// Traitement du tableau des résultats triés
$i = 0;
$cpt = 0;
$tabTri[0][0] = '';
$sauveclasse = $resultats2[0]['ID_CLASSE'];
if (count($resultats2 != 0)) {
    foreach ($resultats2 as $tab_eleve) {
        if ($tab_eleve['ID_CLASSE'] != $sauveclasse) {
            $sauveclasse = $tab_eleve['ID_CLASSE'];
            $i++;
        }
        $checked = ($tab_eleve['INSCRIT'] == 'O') ? "disabled checked" : "";
        $color = ($tab_eleve['INSCRIT'] == 'O') ? "class='val'" : (($tab_eleve['INSCRIT'] == 'V') ? "class='non-val'" : "");
        $tabTri[$i][$cpt] = "<tr><td $color>" . $tab_eleve['NOM'] . "</td><td $color>" . $tab_eleve['PRENOM'] . "</td><td $color>" . $tab_eleve['ID_CLASSE'] . "</td><td $color>" . $tab_eleve['INSCRIT'] . "</td><td $color>" . $tab_eleve['AUTORISATION'] . "</td><td $color>" . $tab_eleve['DROIT_IMAGE'] . "</td><td $color><input type='checkbox' class='filled-in' id='filled-in-box" . $cpt . "' name='inscription[]' value=" . $tab_eleve["NO_PARTICIPANT"] . " style='visibility:hidden' " . $checked . "><label for='filled-in-box" . $cpt . "'></label></td></tr>";
        $cpt++;
    }
}
for ($k = 0; $k <= $i; $k++) {
    $tmp = '';
    foreach ($tabTri[$k] as $ligne) {
        $tmp .= $ligne;
    }
    $table_validation .= "<table class='display' name='tableauTri'><tbody>" . $tmp . "</tbody></table>";
}
?>

<body>
    <div class="main">
        <!-- =================== Menu =================== -->
        <?php include("../include/nav.php") ?>
        <div>
            <center>
                <h1>Tableau récapitulatif du nombre d'inscrits</h1>
            </center>
            <form method="post" action="taborg_val.php?login=<?= $login ?>" id='form'>
                <table class='centered'>
                    <thead>
                        <tr>
                            <th>Classe</th>
                            <th>Nombre d'inscrits</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $table_nbparticipants ?>
                    </tbody>
                </table>

                <br>
                <br>

                <caption><h1 class='centrer'>Tableau récapitulatif des élèves</h1>
                    <br/>
                    <h4 class='centrer'><font color='red'>Nombre d'inscrits: <?php echo $nbeleves ?> &nbsp;Nombre d'élèves validés: <?php echo $nbelevesV ?></font></h4></caption>

                <?= $table_validation ?>
                </center>
                <br/>
                <br/>
                <center>
                    <button name='trier' type='button' class='waves-effect waves-light btn'>Trier la classe</button>
                    <button name='toutCocher' type='button' class='waves-effect waves-light btn'>Tout cocher</button>
                    <button name="Enregistrer" type='button' class='waves-effect waves-light btn'>Enregistrer</button>
                </center>
        </div>
    </form>

    <!-- =================== Footer =================== -->
    <?php include("../include/footer.php") ?>


    <script>
        $(document).ready(function ()
        {
            // Modification de la bulle d'information selon la date
            var d = new Date();
            courante = new Date(d.getFullYear(), d.getMonth(), d.getDate());
            // Evaluation des chaînes de caractères en code pour être réutilisables
            eval(sessionStorage.ccf);
            eval(sessionStorage.courante);
            if (courante > ccf)
            {
                location.href = "taborg_res.php?login=<?= $login ?>";
            }

            // Gestion intelligente des checkbox
            $("button[name='toutCocher']").click(function ()
            {
                var cpt_ch = 0;
                var cpt_un = 0;
                var bout = $("button[name*='toutCocher']");
                var sel = $("div.collapsible-body[style*='display: block'] input[type=checkbox]");
                sel.each(function ()
                {
                    if (!$(this).is(':disabled'))
                    {
                        if (this.checked)
                            cpt_ch++;
                        else
                            cpt_un++;
                    }
                });
                var length = cpt_ch + cpt_un;
                if (cpt_ch == length)
                {
                    $("div.collapsible-body[style*='display: block'] input:checkbox:not(:disabled)").prop('checked', false);
                    bout.html('Tout cocher');
                } else if (cpt_un == length)
                {
                    $("div.collapsible-body[style*='display: block'] input:checkbox:not(:disabled)").prop('checked', true);
                    bout.html('Tout décocher');
                } else
                {
                    if (cpt_ch >= length / 2)
                    {
                        $("div.collapsible-body[style*='display: block'] input:checkbox:not(:disabled)").prop('checked', true);
                        bout.html('Tout décocher');
                    } else
                    {
                        $("div.collapsible-body[style*='display: block'] input:checkbox:not(:disabled)").prop('checked', false);
                        bout.html('Tout cocher');
                    }
                }
            });

            // Réinitalise le texte du bouton de cochage - entrée du panneau
            $("div.collapsible-header").click(function ()
            {
                var total = $(this).next().find("input:checkbox:not(:disabled)").length;
                var coches = $(this).next().find("input:checkbox:not(:disabled):checked").length;
                if (coches == total)
                    $("button[name*='toutCocher']").html('Tout décocher');
                else if (coches == 0)
                    $("button[name*='toutCocher']").html('Tout cocher');
                else if (total - coches == 0)
                    $("button[name*='toutCocher']").html('Tout cocher');
                else if (coches >= total / 2)
                    $("button[name*='toutCocher']").html('Tout cocher');
                else
                    $("button[name*='toutCocher']").html('Tout décocher');
                $(this).next().find("input:checkbox:not(:disabled)").change(function ()
                {
                    var total = $(this).parents('tbody').find("input:checkbox:not(:disabled)").length;
                    var coches = $(this).parents('tbody').find("input:checkbox:not(:disabled):checked").length;
                    if (coches >= total / 2)
                        $("button[name*='toutCocher']").html('Tout cocher');
                    else if (coches == total)
                        $("button[name*='toutCocher']").html('Tout décocher');
                    else if (coches == 0)
                        $("button[name*='toutCocher']").html('Tout cocher');
                    else if (total - coches == 0)
                        $("button[name*='toutCocher']").html('Tout cocher');
                    else
                        $("button[name*='toutCocher']").html('Tout décocher');
                });
            });

            // Permet de trier le tableau
            $("button[name='trier']").click(function ()
            {
                if ($("footer table.display[name*='tableauTri']").length == 0)
                {
                    $("table.display[name*='tableauTri']").each(function ()
                    {
                        $('footer').append($(this)[0].outerHTML);
                    });
                }
                $("footer div[id*='temp']").html($("div.collapsible-body[style*='display: block'] table tbody").html());
                var index = $("div.collapsible-body[style*='display: block']").parents('li').index();
                $("div.collapsible-body[style*='display: block'] table tbody").html($("footer table[name='tableauTri']").eq(index).find('tbody').html());
            });

            // Permet de soumettre le formulaire
            $("button[name='Enregistrer']").click(function ()
            {
                $('#form').submit();
            });
        });
    </script>       