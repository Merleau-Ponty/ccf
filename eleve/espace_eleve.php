<?php
#================== Header et Connection base de données ==================
include '../include/BDD.php';
include '../include/header.php';
$conn = new BDD('ccf');

#================== Si inscription ou desinsciption n'a pas été activé ==================
if (!isset($_POST['Inscription']) && !isset($_POST['Desinscription'])) {
    // Initialisation des variable
    $msg = '';
    $login = $_GET['login'];

    // Récuparation des données pour l'élève
    try {
        $resultat = $conn->select("SELECT INSCRIT, NOM, PRENOM, ID_CLASSE FROM PARTICIPANTS WHERE LOGIN_ELEVE='$login';");
    } catch (PDOException $e) {
        echo "Erreur {$e->getCode()}: " + $e->getMessage();
    }

    // Statut de l'élève
    if ($resultat[0]['INSCRIT'] == 'V' || $resultat[0]['INSCRIT'] == 'O') {
        $statut = 'INSCRIT';
    } else {
        $statut = 'NON INSCRIT';
    }

    // Variable pour le titre de la page
    $nom = $resultat[0]['NOM'];
    $prenom = $resultat[0]['PRENOM'];
    $classe = $resultat[0]['ID_CLASSE'];
    $titre = $nom . " " . $prenom . " " . $classe;
} #================== Si inscription ou desinsciption a été activé ==================
else {
    // Initialisation des variable
    $login = $_POST['nom'];

    // Récuparation des données pour l'élève
    try {
        $resultat1 = $conn->select("SELECT INSCRIT, NOM, PRENOM, ID_CLASSE FROM PARTICIPANTS WHERE LOGIN_ELEVE='$login';");
    } catch (PDOException $e) {
        echo "Erreur {$e->getCode()}: " + $e->getMessage();
    }

    // Statut de l'élève
    if ($resultat1[0]['INSCRIT'] == 'V' || $resultat1[0]['INSCRIT'] == 'O') {
        $statut = 'INSCRIT';
    } else {
        $statut = 'NON INSCRIT';
    }

    // Variable pour le titre de la page
    $nom = $resultat1[0]['NOM'];
    $prenom = $resultat1[0]['PRENOM'];
    $classe = $resultat1[0]['ID_CLASSE'];
    $titre = $nom . " " . $prenom . " " . $classe;
}

#================== Si inscription est activé ==================
if (isset($_POST['Inscription'])) {
    // Initialisation des variable
    $msg = '';

    // Verificatioon du statut de l'élève
    if ($resultat1[0]['INSCRIT'] == 'V' || $resultat1[0]['INSCRIT'] == 'O') {
        $msg = 'Tu es déjà inscrit';
    } else {
        $msg = 'Inscription réussie !';
        try {
            $conn->insert("UPDATE PARTICIPANTS SET INSCRIT='V', AUTORISATION='N', DROIT_IMAGE='N', DOSSIER='N' WHERE LOGIN_ELEVE='$login';");
        } catch (PDOException $e) {
            echo "Erreur {$e->getCode()}: " + $e->getMessage();
        }
    }

    // Rechargement de la page pour mettre à jour le statut de l'élève
    @header("Refresh:3; url=espace_eleve?login=$login");
}

// Si le button desinscription est activé
if (isset($_POST['Desinscription'])) {
    // Initialisation des variable
    $msg = '';

    // Verificatioon du statut de l'élève
    if ($resultat1[0]['INSCRIT'] == 'V' || $resultat1[0]['INSCRIT'] == 'O') {
        $msg = 'Desinscription réussie !';
        try {
            $conn->insert("UPDATE PARTICIPANTS SET INSCRIT='N', AUTORISATION='N', DROIT_IMAGE='N', DOSSIER='N' WHERE LOGIN_ELEVE='$login';");
        } catch (PDOException $e) {
            echo "Erreur {$e->getCode()}: " + $e->getMessage();
        }
    } else {
        $msg = 'Tu n\'est pas inscrit.';
    }

    // Rechargement de la page pour mettre à jour le statut de l'élève
    @header("Refresh:3; url=espace_eleve?login=$login");
}
?>

<!-- =================== Contenu de la page =================== -->
<body>
    <div class='main'>
        <!-- =================== Menu =================== -->
        <?php include '../include/nav.php' ?>
        <center><h2 class='centrer'><?= $titre; ?></h2></center>
        <div class='row'>
            <form class='col s12' action='espace_eleve.php' method='post' id='test'>
                <div class='row'>
                    <br><br><br>

                    <!-- =================== Input caché pour garder le Login de l'élève =================== -->
                    <input hidden="" type="text" name="nom" value="<?= $login ?>">

                    <!-- =================== statut =================== -->
                    <div class="input-field col s6">
                        <center>
                            <input type="text" name="statut" disabled="disabled" value="Statut : <?= $statut ?>" style="text-align: center"/>
                        </center>
                    </div>

                    <!-- =================== Inscription et Désinscription =================== -->
                    <div class="input-field col s6">
                        <center>
                            <button name="Inscription" class="waves-effect waves-light btn">Inscription</button>
                            <button name="Desinscription" class="waves-effect waves-light btn">Désinscription</button>
                        </center><br>
                    </div>

                    <!-- =================== Autorisation parentale =================== -->
                    <div>
                        <center>
                            <button name="autorisation" class="waves-effect waves-light btn" onclick="window.open('autorisationparents.pdf'); return false;">Clique ici pour télécharger l'autorisation parentale</button>
                        </center>
                    </div>

                    <!-- =================== Message pour l'inscription et la désinscription =================== -->
                    <div class="input-field col s6">
                        <center>
                            <?php echo $msg; ?>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- =================== Footer =================== -->
    <?php include('../include/footer.php') ?>