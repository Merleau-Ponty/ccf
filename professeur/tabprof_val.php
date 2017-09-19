<?php
include_once "../include/BDD.php";
$connexion = new BDD('ccf');
include("../include/header.php");
if (isset($_GET['login']))
    $login = $_GET['login'];
else
    $login = $_POST['login'];
$login = strtoupper($login);
?>

<body>
    <div class="main">

        <?php include("../include/nav.php"); ?>

        <div class="cycle">
            <br/>

            <form method="post" action="tabprof_val.php?login=<?= $login ?>">
                <center>
                    <caption><strong><h3>Tableau récapitulatif des élèves</h3></strong></caption>


                    <?php
                    echo"<input type='hidden' name='login' value=$login></input>";

                    // on récupère toutes les checkbox cochées
                    $checkboxes = isset($_POST['inscription']) ? $_POST['inscription'] : array();
                    // Initalisation des variables pour éviter qu'elles deviennent locales lors de leur déclaration
                    $requete = '';
                    $requete2 = '';
                    /* $sauveclasse = ''; */

                    foreach ($checkboxes as $value) {
                        $requete = "UPDATE PARTICIPANTS set INSCRIT = 'O', AUTORISATION = 'O', DROIT_IMAGE = 'O', DOSSIER = 'O' where NO_PARTICIPANT = '" . $value . "'";
                        $inscription = $connexion->insert($requete);
                    }


                    $requete = "select NO_PARTICIPANT,PARTICIPANTS.NOM,PARTICIPANTS.PRENOM,PARTICIPANTS.ID_CLASSE, PARTICIPANTS.INSCRIT,AUTORISATION,DROIT_IMAGE from PARTICIPANTS
									inner join CLASSE on PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
									inner join PROFESSEURS on CLASSE.LOGIN = PROFESSEURS.LOGIN
									where CLASSE.LOGIN='$login'
                                    and INSCRIT IN ('O','V','N')
									order by PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";
                    $requete2 = "select NO_PARTICIPANT,PARTICIPANTS.NOM,PARTICIPANTS.PRENOM,PARTICIPANTS.ID_CLASSE, PARTICIPANTS.INSCRIT,AUTORISATION,DROIT_IMAGE 
                                                                        from PARTICIPANTS
									inner join CLASSE on PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
									inner join PROFESSEURS on CLASSE.LOGIN = PROFESSEURS.LOGIN
									where CLASSE.LOGIN='$login'
                                    and INSCRIT IN ('O','V','N')
									order by PARTICIPANTS.ID_CLASSE,
                                    case INSCRIT
                                    when 'O' then 1
                                    when 'V' then 2
                                    when 'N' then 3
                                    end,
                                    PARTICIPANTS.NOM";


                    $resultats = $connexion->select($requete);
                    // Stockage des valeurs du tableau une fois trié
                    $resultats2 = $connexion->select($requete2);

                    if (count($resultats2) != 0) {

                        // Récupération de la première classe
                        $sauveclasse = $resultats[0]['ID_CLASSE'];
                        // Pour les terminales
                        if ($sauveclasse == 'TERM') {
                            $requete = "select NO_PARTICIPANT,PARTICIPANTS.NOM,PARTICIPANTS.PRENOM,PARTICIPANTS.ID_CLASSE,INSCRIT,AUTORISATION,DROIT_IMAGE from PARTICIPANTS
                                        inner join CLASSE on PARTICIPANTS.ID_CLASSE = CLASSE.ID_CLASSE
                                        inner join PROFESSEURS on CLASSE.LOGIN = PROFESSEURS.LOGIN
                                        where CLASSE.LOGIN='$login'  
                                        order by PARTICIPANTS.ID_CLASSE , PARTICIPANTS.NOM";
                            $resultats = $connexion->select($requete);

                            // on dit qu'on veut que le résultat soit récupérable sous forme de tableau)
                            $sauveclasse = $resultats[0]['ID_CLASSE'];
                        }
                        $cpt = 0;
                        echo "<ul class='collapsible' data-collapsible='accordion'>
                                <li>
                                    <div class='collapsible-header'>$sauveclasse</div>
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
                        foreach ($resultats as $tab_eleve) {
                            $cpt += 1;
                            if ($tab_eleve['ID_CLASSE'] <> $sauveclasse) {
                                $sauveclasse = $tab_eleve['ID_CLASSE'];
                                echo "</div></tbody></table></li>
                                <li>
                                    <div class='collapsible-header'>$sauveclasse</div>
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
                            $checked = ($tab_eleve['INSCRIT'] == 'O') ? "disabled checked" : "";
                            $color = ($tab_eleve['INSCRIT'] == 'O') ? "class='val'" : (($tab_eleve['INSCRIT'] == 'V') ? "class='non-val'" : "");
                            echo "<tr><td $color>", $tab_eleve['NOM'], "</td><td $color>", $tab_eleve['PRENOM'], "</td><td $color>", $tab_eleve['ID_CLASSE'], "</td><td $color>", $tab_eleve['INSCRIT'], "</td><td $color>", $tab_eleve['AUTORISATION'], "</td><td $color>", $tab_eleve['DROIT_IMAGE'], "</td><td $color><input type='checkbox' class='filled-in' id='filled-in-box" . $cpt . "' name='inscription[]' value=" . $tab_eleve["NO_PARTICIPANT"] . " style='visibility:hidden' " . $checked . "><label for='filled-in-box" . $cpt . "'></label></td></tr>";
                        }
                        echo "</tbody></table></li>";
                    }
                    // Traitement du tableau des résultats triés
                    $i = 0;
                    $cpt = 0;
                    $tabTri[0][0] = '';
                    if (count($resultats2) != 0) {

                        $sauveclasse = $resultats2[0]['ID_CLASSE'];
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
                        echo "<table class='display' name='tableauTri'><tbody>" . $tmp . "</tbody></table>";
                    }
                    ?>
                    </ul>
                </center>
                <br/>

                <center>
                    <button name='trier' type='button' class='waves-effect waves-light btn'>Trier la classe</button>
                    <button name='toutCocher' type='button' class='waves-effect waves-light btn'>Tout cocher</button>
                    <button name="Enregistrer" class='waves-effect waves-light btn'>Enregistrer</button>
                </center>
            </form>
        </div>
    </div>
</section>
<!-- ============================== Footer ================================= -->
<?php include("../include/footer.php"); ?>

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
            location.href = "tabprof_res.php?login=<?= $login ?>";
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