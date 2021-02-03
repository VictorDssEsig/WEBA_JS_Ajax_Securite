<?php
/** 
 ** Salon de coiffure Eylsée - Victor, Jarod et Maxime
 */
  require 'includes/dbfunctions.php';
  session_start();

if(isset($_SESSION['telephone']))
{
  unset($_SESSION['telephone']);
}

  if($_SERVER['HTTP_HOST'] != 'localhost'){
    //header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //exit;
    if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
    {
      //Tell the browser to redirect to the HTTPS URL.
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
      //Prevent the rest of the script from executing.
      exit;
    }
  }
  
  $admin=isset($_SESSION['COI_POSTE'])? true:false;
  
  if (isset($_SESSION["COI_POSTE"])){
    if ($_SESSION["COI_POSTE"] == "Patronne" OR $_SESSION["COI_POSTE"] == "Coiffeur superieur"){
      $modificationPossible = true;
    }
    else{
      $modificationPossible = false;
    }
  }else{
    $modificationPossible = false;
  }



  if(!isset($_SESSION['set'])){
  $_SESSION['set'] = false;}
?>

<!--
 * * * * * * * * * * * * * * * * * *
 * Maxime Perrod 07.06.2020
 * * * * * * * * * * * * * * * * * *
-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="HandheldFriendly" content="true">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>Accueil</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet"> 
    </head>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    
<body style="background-color: #fed136;">
 <div id="carouselExampleIndicators" class="carousel row slide my-carousel my-carousel " data-ride="carousel" >
    <nav class="navbar navbar-expand-md navbar-dark fixed-top" id="banner" >
      <a class="navbar-brand" href="index"><span>Salon coiffure Elysée</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto">
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
                    <a class="nav-link dropdown-toggle" style="cursor: pointer;" id="navbardrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> rendez-vous</a>
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
                <li class="nav-item">
                <a class="nav-link" href="contact">Contact</a>
                </li>
            </ul>
        </div>
    </nav>
          <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner" role="listbox">
              <div class="carousel-item active" style="background-image: url('Ressources/ImageSalon/imgAccueil1.jpg');background:no-repeat cover"></div>
              <div class="carousel-item " style="background-image: url('Ressources/ImageSalon/imgAccueil2.jpg');background:no-repeat cover;"></div>
              <div class="carousel-item " style="background-image: url('Ressources/ImageSalon/imgAccueil3.jpg');background:no-repeat cover"></div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
          </div>
  </div>
  
  <div class="jumbotron row jb-milieu-Acceuil text-center" style="height:400px;background-color: #fed136;">
    <div class="container ct-milieu-Acceuil">
    <hr class="my-4" style="border-top: 2px solid black">
    <hr class="my-4" style="border-top: 2px solid black">
      <h1 class="display-3">Salon coiffure Elysée</h1>
      <p  style="font-weight: bold;">Venir chez Olga c'est bien plus que venir dans un salon de coiffure</p>
      <hr class="my-4" style="border-top: 2px solid black">
      <hr class="my-4" style="border-top: 2px solid black">
    </div>
  </div>

  <div class="row galerie-Acceuil">
      <div class="gallery" id="gallery">
        <div class="mb-4 pics animation all 2">
          <img class="img-fluid" src="Ressources\ImageSalon\1.jpg" alt="Card image cap">
        </div>
        <div class="mb-4 pics animation all 1">
          <img class="img-fluid" src="Ressources\ImageSalon\6.jpg" alt="Card image cap">
        </div>
        <div class="mb-4 pics animation all 1">
          <img class="img-fluid" src="Ressources\ImageSalon\7.jpg" alt="Card image cap">
        </div>
        <div class="mb-4 pics animation all 2">
          <img class="img-fluid" src="Ressources\ImageSalon\4.jpg" alt="Card image cap">
        </div>
        <div class="mb-4 pics animation all 2">
          <img class="img-fluid" src="Ressources\ImageSalon\DSC_5045.jpg" alt="Card image cap">
        </div>
        <div class="mb-6 pics animation all 1">
          <img class="img-fluid" src="Ressources\ImageSalon\7.jpg" alt="Card image cap">
        </div>
      </div>
  </div>
<div class="row card card-image-middle">
    <div class="text-white text-center py-5 px-4">
      <div>
        <h1 class="card-title h1-responsive pt-3 mb-5 font-bold" style="color: #333"><strong>Salon Coiffure Elysee Une toute  autre Vision de la coiffure</strong></h2>
        <p class="mx-5 mb-5" style="color: #333;font-size:110%">Dans ce contexte personnel, Olga va alors vous ouvrir les portes de son univers .Il est fort probable que vous entendiez parler d'Odessa et que vous partagiez un éventail d'émotions au travers d'anecdotes ou d'épisodes de son vlog, le tout en dégustant un café
En venant chez Olga, vous ressortirez avec un style de coiffure rafraichi, mais aussi l'esprit léger avec le plaisir d'un moment de détente et de tranquillité qui aura été totalement dédié .
        </p>
      </div>
    </div>
</div>

<?php 
    if (isset($_POST["AjouterImageS"]) && !$_SESSION['set']){
      if (isset($_FILES['monfichierImage']) AND $_FILES['monfichierImage']['error'] == 0){
        $infosfichier = pathinfo($_FILES['monfichierImage']['name']); //path info renvois une array donc on stock l'extension dans exension_upload
        $extension_upload = $infosfichier['extension'];
        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
        $name = $_POST["nomphoto"];
        $file =$name. '.' .$extension_upload;

          if (in_array($extension_upload, $extensions_autorisees)){ //On verifie que le fichier fait partis des extensions autorisés
            // On peut valider le fichier et le stocker définitivement
            move_uploaded_file($_FILES['monfichierImage']['tmp_name'], 'Ressources/ImageSalon/' . $file);

            insertImage( $_POST["nomphoto"],'Ressources/ImageSalon/' . $file);

          }else{
            ?><script>window.alert("L'extension utilisé n'est pas autorisée, les types d'extensions autorisées sont: 'jpg', 'jpeg', 'gif', 'png'");</script><?php
          }
      }else{
        ?><script>window.alert("Une erreur s'est produite");</script><?php
      }
    }
?>
<?php     
if(isset($_SESSION['COI_POSTE'])){
  if($_SESSION['COI_POSTE'] =='Patronne'){
    if(isset($_GET["Image"])){
        supprimerImageAcceuil($_GET["Image"]);
    }
  }
}
$YaDesPhotos = getAllImageAcceuil(); 
if(!empty($YaDesPhotos)){ ?>
<div class="GalerieAdmin">
  <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">

    <div class="carousel-inner">       
    <?php $compteur = 0;
       foreach(getAllImageAcceuil() as $photo): 
        if ($compteur == 0){ ?>
          <div class="carousel-item active" style="background-image:url('<?= $photo['IMG_CHEMIN']?>');background:no-repeat cover; z-index:-2">
          </div>
          <?php if($admin=='Patronne'){?>
              <a href="index?Image=<?=$photo['IMG_ID']?>" style="font-size: 200%; z-index:1"  class="fa fa-trash bottomright text-center text-dark"></a>
            <?php } ?>
        <?php } else{ ?> 
          <div class="carousel-item" style="background-image:url('<?= $photo['IMG_CHEMIN']?>');background:cover no-repeat; z-index:-2">
          </div>
          <?php if($admin=='Patronne'){?>
              <a href="index?Image=<?=$photo['IMG_ID']?>" style="font-size: 200%; z-index:1" class="fa fa-trash bottomright texte-center text-dark" ></a>
            <?php } ?>
        <?php 
        } 
        $compteur++;
        endforeach;
        ?>

    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev" style="z-index:0">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next" style="z-index:0">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  </div>
</div>
<?php } ?>    
    
<?php
if(isset($_SESSION['COI_POSTE'] )) {
  if($_SESSION['COI_POSTE'] =='Patronne'){
?>
<button data-toggle="modal" data-target="#AjouterImage" class="btn btn-dark border border-dark" style="width:180px;max-height:50px">Ajouter Image</button>
<div class="modal fade bd-example-modal-lg" data-backdrop="false" id="AjouterImage" tabindex="-1" role="dialog" aria-labelledby="AjouterImage" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModifierParagraphe">Ajouter une Image <span id="mTitre"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="index" enctype="multipart/form-data">
            <div class="row">
              <div class="col-lg-6 col-md-6">
                <h5>Nom de L'image: </h5>
                <div class="form-group">
                  <input type="text" class="form-control" id="nomphoto" name="nomphoto" required>
                </div>
              </div>
            </div>
            <br>
            <div class="col-12">
              <div class="custom-file">
                <input type="file" name="monfichierImage" /><br />
              </div>
            </div>
        <br> 
        <div class="modal-footer">
          <input id="mID" name="mID" type="text" value="" hidden="true">
          <button type="button" class="btn btn-secondary border border-dark" data-dismiss="modal">Fermer</button>
          <button type="submit" name="AjouterImageS" class="btn btn-primary">Ajouter</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>   
<?php }
        } ?>  
</body>

<div class="row">
<?php include('includes/footer.php');?>
</div>



<script type="text/javascript" src="js/Navbarscroll.js"></script>
<script type="text/javascript" src="js/galerieAcceuil.js"></script>
<script type="text/javascript" src="js/galerie2.js"></script>
<script>
  (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
  function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
  e=o.createElement(i);r=o.getElementsByTagName(i)[0];
  e.src='//www.google-analytics.com/analytics.js';
  r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
  ga('create','UA-52746336-1');ga('send','pageview');
  var isCompleted = {};
  function sampleCompleted(sampleName){
    if (ga && !isCompleted.hasOwnProperty(sampleName)) {
      ga('send', 'event', 'WebCentralSample', sampleName, 'completed'); 
      isCompleted[sampleName] = true;
    }
  }
</script>


</html>