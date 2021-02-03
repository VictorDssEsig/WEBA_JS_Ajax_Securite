<?php
/** 
 ** Salon de coiffure Eylsée - Victor, Jarod et Maxime
 */
  require 'dbfunctions.php';
  session_start();
isset($_SESSION['COI_MAIL'])? $admin = true: $admin = false;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?=$title?></title>
    
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet"> 
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
        <!-- Google Fonts Roboto -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
        <!-- Bootstrap core CSS -->
        <link href="css/addons/datatables2.min.css" rel="stylesheet">
        

    </head>

    <body>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-md navbar-dark fixed-top" id="banner" style="background-color: #383838;z-index: 1;">
            <!-- Brand -->
            <a class="navbar-brand" href="index"><span>Salon de coiffure Elysée</span></a>

            <!-- Toggler/collapsibe Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

            <!-- Navbar links -->
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav ml-auto" style="z-index: 2;">
                        <?php if (isset($_SESSION["COI_POSTE"])){?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Admin
                                </a>
                            
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="listedesclients">Liste client</a>
                                <a class="dropdown-item" href="listecoiffeur">Liste coiffeur</a>
                                <a class="dropdown-item" href="agenda?coiffeur=<?=$_SESSION['COI_ID']?>">Agenda</a>
                                <a class="dropdown-item" href="ModifierInfo">Informations personnelles</a>
                                <a class="dropdown-item" href="Sedeconnecter">Se deconnecter </a>
                            </div>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index">Accueil</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="index" id="navbardrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> rendez-vous</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="priserdv1">Prendre Rendez-vous</a>
                                <a class="dropdown-item" href="VoirRendezvousForm">Voir Rendez-vous</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="parcours">Parcours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="liste%20des%20prix">Liste des prix</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="boutique">Boutique</a>
                        </li>
                        <!-- Dropdown -->
                        <li class="nav-item">
                            <a class="nav-link" href="contact">Contact</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </body>
    <br><br>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/addons/datatables2.min.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/createInput.js"></script>
