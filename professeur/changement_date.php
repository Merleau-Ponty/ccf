<?php 
include_once "../include/BDD.php";
$conn = new BDD('ccf');
// Changement de date CCF
if(isset($_POST['date']))
{
    // Ecriture de la date & redirection
    $tabDate = explode("-", $_POST['date']);
    $date = $tabDate[2].'/'.$tabDate[1].'/'.$tabDate[0];
    file_put_contents('../include/date_ccf.dat', $date);
    include '../include/header.php';
    echo "<body><div class='main centrer'>";
    include '../include/nav.php';
    echo "<h1>Date modifiée</h1>\n<p>Redirection vers l'accueil<p></div>";
    include '../include/footer.php';
    @header("Refresh: 2;URL=../index.php");
    exit;
}
?>

<?php include("../include/header.php") ?>

        <body>
            <div class="main">

                <?php include("../include/nav.php") ?>

				<h1 class='center'>Changement date CCF</h1>
                
                            <form class='col s12' action='changement_date.php' id='form' method='post'>
                                <div class='row champ'>
                                    <label for='date'>Nouvelle date de la CCF</label>
                                    <input type='date' name='date' id='date'>
                                </div>
                                <div class='row centrer'>
                                    <button type='submit' class='waves-effect waves-light btn'>Modifier</button>
                                </div>
                            </form>
                        
            </div>

<!-- ============================== Footer ================================= -->
<?php include("../include/footer.php") ?>

<script type='text/javascript'>
    $(document).ready(function()
    {
        // CSS dynamique
        var inpDate = $('div.row.champ');
        // Enlever la classe en CSS enlève le centrage
        inpDate.removeClass('champ');
        inpDate.width('150px');
        
        $('.collapsible').collapsible(
        {
            accordion: false
        });
        
        // Gestion de la date afin qu'elle s'applique immédiatement
        $("button[type='submit']").click(function(e)
        {
            e.preventDefault();
            var date = $("input[type='date']").val();
            // Vérification de la saisie pour Firefox qui ne prend pas en charge l'input de type date
            if(date != '')
            {
                if(bowser.firefox)
                {
                    if(!numerical(date))
                        Materialize.toast('Entrez une date valide (jj-mm-aaaa)', 2000);
                    else
                    {
                        appliquerDate(date, 'firefox');
                    }
                }
                else
                {
                    appliquerDate(date, '');
                }
            }
            else
            {
                Materialize.toast('Choisissez une date', 2000);
            }
        });
        
        function numerical(date)
        {
            var t = date.split('-'); 
            if(!t[2] || !t[1])
                return false;
            return (t[2].length == 4 && t[1].length == 2 && t[0].length == 2 && !isNaN(+t[0]) && !isNaN(+t[1]) && !isNaN(+t[2])) ? true : false;
        }
        
        function appliquerDate(date, chaine)
        {
            if(chaine == 'firefox')
            {
                var d_ccf = date.split('-');
                sessionStorage.ccf = "var ccf = new Date(" + d_ccf[2] + ',' + (d_ccf[1]-1) + ',' + d_ccf[0] + ");";
                $("input[type='date']").val(d_ccf[2] + '-' + d_ccf[1] + '-' + d_ccf[0]);
            }
            else
            {
                var d_ccf = date.split('-');
                sessionStorage.ccf = "var ccf = new Date(" + d_ccf[0] + ',' + (d_ccf[1]-1) + ',' + d_ccf[2] + ");";
            }
            sessionStorage.courante = "var courante = new Date();";
            eval(sessionStorage.ccf);
            eval(sessionStorage.courante);
            $('#form').submit();
        }
    });
</script>