<?php 
if(!isset($_SESSION) && !isset($_POST['classe'])) 
{
   session_start(); 
}
include '../include/BDD.php';
$conn = new BDD('ccf');
if(isset($_POST['classe']) && isset($_POST['nom']))
{
    $msg = '';
    $res = '';
    $no_eleve =  $_POST['nom'];
    // Modification de la BDD
    try
    {   //echo "select INSCRIT from PARTICIPANTS where NO_PARTICIPANT='{$no_eleve}';";
        $res = $conn->select("select INSCRIT from PARTICIPANTS where NO_PARTICIPANT='{$no_eleve}' ;");
//        var_dump($res);
    }
    catch(PDOException $e)
    {
        echo "Erreur {$e->getCode()}: " + $e->getMessage();
    }
    if($res[0]['INSCRIT'] == 'V' || $res[0]['INSCRIT'] == 'O')
    {
        $msg = 'Élève déjà inscrit';
    }
    else
    {
        $msg = 'Inscription réussie !';
        try
        {
            $conn->insert("update PARTICIPANTS set INSCRIT='V', AUTORISATION='N', DROIT_IMAGE='N', DOSSIER='N' where NO_PARTICIPANT='{$no_eleve}' ;");
        }
        catch(PDOException $e)
        {
            echo "Erreur {$e->getCode()}: " + $e->getMessage();
        }
    }
    // Page de confirmation et redirection
    include '../include/header.php';
    echo "<body><div class='main centrer'>";
    include '../include/nav.php';
    echo "<h1>$msg</h1>\n<p>Redirection vers l'accueil<p></div>";
    include '../include/footer.php';
    @header("Refresh: 2;URL=inscription_eleve.php");
    exit;
}
// PHP pour l'Ajax
if(isset($_POST['classe']))
{
    // Conversion résultats SQL en JSON
    $req = "select NO_PARTICIPANT, NOM, PRENOM from PARTICIPANTS where ID_CLASSE='{$_POST['classe']}' order by NOM;";
    $res = $conn->select($req);  
    echo json_encode($res);
    exit;
}
include '../include/header.php';
$req = 'select NOM_CLASSE, ID_CLASSE from CLASSE order by NOM_CLASSE';
try 
{
    $res = $conn->select($req);
}
catch(PDOException $e)
{
    echo "Problème d'exéuction de la requête";
}
?>
<body>
    <div class='main'>
        <?php include '../include/nav.php' ?>
        <h2 class='centrer'>Inscription</h2>
            <div class='row'>
                <form class='col s12' action='inscription_eleve.php' method='post' id='test'>
                    <div class='row'>
                            <label class='champ'>Classe de l'élève</label>
                            <select class="browser-default champ" id='classe' name='classe'>
                                <option value="" disabled selected>Choisir une classe</option>
                                <?php
                                for($i=0; $i<count($res); $i++)
                                {
                                    echo "<option value='{$res[$i]['ID_CLASSE']}'>".$res[$i]['NOM_CLASSE']."</option>\n";
                                }
                                ?>
                            </select>
                    </div>
                    <div class='row' id='nomGen'>
                        
                    </div>
                </form>
            </div>
    </div>
    
    <?php include '../include/footer.php' ?>
    
    <script type="text/javascript">
        $(document).ready(function()
        {
            // CSS dynamique
            var div = $('div.row');
            div.width('600px');
            
            // Lors du changement de valeur du select
            $('#classe').on('change', function()
            {
                $.ajax(
                {
                    url:'inscription_eleve.php',
                    type: 'post',
                    data: 'classe=' + $('#classe').val(),
                    dataType: 'json',
                    success: function(res)
                    {
                        var html = "<label class='champ'>Nom de l\'élève</label>\n<select class='browser-default champ' id='nom' name='nom'>\n<option value='' disabled selected>Choisir un élève</option>\n";
                        $.each(res, function(cle, valeur)
                        {
                            html += "<option value="+valeur.NO_PARTICIPANT+">" + valeur.NOM + " " + valeur.PRENOM + "</option>\n";
                        });
                        $('#nomGen').html(html);
                        // Affichage du bouton de validation
                        var cpt = false;
                        $('#nom').on('change', function()
                        {
                            if($(this).val() != '' && cpt == false)
                            {
                                $('form').append("<div class='centrer'><button class='waves-effect waves-light btn' type='submit'>S\'inscrire</button>");
                                cpt = true;
                            }
                        });
                    },
                    error: function(){alert('Erreur Ajax')}
                });
            });
        });
    </script>
</body>