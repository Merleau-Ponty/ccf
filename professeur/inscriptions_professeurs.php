<?php
session_start();
$message="";

if (isset($_POST["username"]))
{
    $_SESSION['login']=$_POST["username"];
	include '../include/BDD.php';
	include 'verif_inscription_prof.php';
}
include("../include/header.php");

?>
<body>
<div class="main">

  <!--==============================Contenu================================-->
	<?php include("../include/nav.php"); ?>

<!--=========================Authentification============================-->
        <center><h2>Authentification</h2></center>
        <div class="row">
			<form method="post" action="inscriptions_professeurs.php" name="loginform" id="loginform" class="col s12">
                
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
				<center><button onclick="document.getElementById('form').submit()" name="Connexion" class="waves-effect waves-light btn">Connexion</button></center>
            </form>
        </div>
    
	<?php echo($message); ?>
    
			</div>
        </section>

<!--==============================footer=================================-->
<?php include('../include/footer.php') ?>


<script type='text/javascript'>
        // Gestion des parties de l'application - espace élève
        eval(sessionStorage.ccf);
        eval(sessionStorage.courante);
        if(courante > ccf)
        {
            $('h2').empty();
            $('div.row:first').html('<h2 class=\'centrer\'>La course est finie</h2>\n<p class=\'centrer\'>Vous pouvez consulter les résultats, s\'ils sont disponibles</p>');
        }
        else if(courante.getTime() == ccf.getTime())
        {
            $('h2').empty();
            $('div.row:first').html('<h2 class=\'centrer\'>La course est aujourd\'hui</h2>\n<p class=\'centrer\'>N\'oubliez pas d\'y participer si vous vous êtes inscrit</p>'); 
        }
    
        // CSS
        $('label').css('margin-left', '12px');
        $('form > div').width('450px');
    </script>