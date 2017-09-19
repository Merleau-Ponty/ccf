<?php
session_start();
$message = '';
if (isset($_POST["username"]))
{
	include '../include/BDD.php';
	include 'verification_organisateur.php';
}
include("../include/header.php");
?>
<body>
    <div class="main">
	   <?php include("../include/nav.php") ?>
    
        <center><h2>Authentification</h2></center>
        <div class="row">
			<form method="post" action="organisateurs.php" name="loginform" id="loginform" class="col s12">
                
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
				<center><button name="Connexion" class="waves-effect waves-light btn">Connexion</button></center>
            </form>
        </div>
    
	<?php echo($message); ?>
            </div>
        </section>

<!-- ============================== Footer ================================= -->
<?php include("../include/footer.php") ?>

<script type='text/javascript'>
    $(document).ready(function()
    {
        $("button[name='Connexion']").click(function()
        {
            $('form').submit();
        });
        
        // CSS
        $('label').css('margin-left', '12px');
        $('form > div').width('450px');
    });
</script>
