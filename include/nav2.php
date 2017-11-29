<section id="content">
    <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper cyan darken-3">
                <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                <a href="#" class="brand-logo right"><img src="../images/logo-ccf.png"></a>

                <!-- Menu desktop -->
                <ul class="left hide-on-med-and-down">
                    <li><a href="../index.php"><i class="material-icons">home</i></a></li>
                    <li><a href="../eleve/eleves.php">Espace élèves</a></li>
                    <!-- <li><a href="../professeur/professeurs.php">Espace professeurs</a></li>-->
                    <li><a class="dropdown-button" href="../professeur/professeurs.php" data-activates="dropdown1">Espace professeurs<i class="mdi-navigation-arrow-drop-down right"></i></a></li>

                    <!-- sous-menu desktop -->
                    <ul id='dropdown1' class='dropdown-content'>
                        <li><a href="../professeur/inscription_prof.php">Inscription</a></li>
                        <li><a href="../professeur/tabprof_res.php">Inscription élèves</a></li>
                        <li><a href="../professeur/import_prof.php">Importation professeurs</a></li>
                        <li><a href="../eleve/import_eleve.php">Importation élèves</a></li>
                        <li><a href='../professeur/changement_date.php'>Changement date CCF</a></li>
                    </ul>
                    <!-- fin sous-menu desktop -->

                    <li><a href="../organisateur/organisateurs.php">Espace organisateurs</a></li>
                    <li><a href="../resultat/espaceresultat.php">Résultats</a></li>
                </ul>

                <!-- Menu mobile -->
                <ul class="side-nav" id="mobile-demo">
                    <li><a href="../index.php">Accueil</a></li>
                    <li><a href="../eleve/eleves.php">Espace élèves</a></li>
                    <!--<li><a href="../professeur/professeurs.php">Espace professeurs</a></li>-->

                    <!-- sous-menu mobile -->
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header">Espace professeurs<i class="mdi-navigation-arrow-drop-down"></i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="../professeur/inscription_prof.php">Inscription</a></li>
                                        <li><a href="../professeur/tabprof_res.php">Inscription élèves</a></li>
                                        <li><a href="../professeur/import_prof.php">Importation professeurs</a></li>
                                        <li><a href="../eleve/import_eleve.php">Importation élèves</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- fin sous-menu mobile -->

                    <li><a href="../organisateur/organisateurs.php">Espace organisateurs</a></li>
                    <li><a href="../resultat/espaceresultat.php">Résultats</a></li>
                </ul>
            </div>
        </nav>
    </div>
</section>