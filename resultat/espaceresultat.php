<?php


$_SESSION['prof'] = array(0); // permet d'initialer le tableau d'affichage des résultats inséré à 0 lorsque le prof se connecte
$message='';
if (isset($_POST["username"]))
{
	include '../include/BDD.php';
    $connexion=new BDD('ccf');
	include 'verification_resultat.php';
}
include("../include/header.php");
?>
<body>
<div class="main">
	<?php include("../include/nav.php"); ?>
    
    <center><h2>Authentification</h2></center>
        <div class="row">
			<form method="post" action="espaceresultat.php" name="loginform" id="loginform" class="col s12">
                
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

    <script type='text/javascript'>
        $(document).ready(function()
        {
            // CSS
            $('label').css('margin-left', '12px');
            $('form > div').width('450px');
        });
    </script>

<!-- ============================== Footer================================= -->
<?php include("../include/footer.php") ?>
