<?php 
/** 
 ** Salon de coiffure Eylsée - Victor, Jarod et Maxime
 */

$title = "Elysée - Article";
include('includes/header.php');

if (isset($_SESSION["COI_POSTE"])){
  if ($_SESSION["COI_POSTE"] == "Patronne"){
    $modificationPossible = true;
  }
  else{
    $modificationPossible = false;
  }
}else{
  $modificationPossible = false;
}


if($modificationPossible){
  if(isset($_POST['suppr'])){
    if(!empty(getOneArticleID($_GET['id']))){
      suppressionLogiqueArticle($_GET['id']);
    }
  }
}



if(isset($_GET['id']))
{
$article = getOneArticleID($_GET['id']);
}

?>




<br>  

<body style="background-color: #fed136;">
  
<div class="container" >
    <div class="card">
    <form method="POST" action="article?id=<?= $article['ART_ID'] ?>"  enctype="multipart/form-data">
    <div class="row no-gutters">
        <div class="col-4 border border-dark">
        <div id="carouselExampleControls<?= $article['ART_ID'] ?>"  class="carousel slide marginauto"  style="height: 600px; width: 350px; object-fit: contain;">
                    <div class="carousel-inner">

                      <?php 
                      $compteur = 0;
                      foreach(getAllImgArticle($article['ART_ID']) as $photo): 
                        if ($compteur == 0){ ?>
                          <div class="carousel-item active">
                            <img class="d-block w-100" src="<?=$photo["IMG_CHEMIN"]?>" alt="slide" style="height: 600px; width: 350px; object-fit: contain;">
                          </div>
                        <?php } else{ ?> 
                          <div class="carousel-item">
                            <img class="d-block w-100" src="<?=$photo["IMG_CHEMIN"]?>" alt="slide" style="height: 600px; width: 350px; object-fit: contain;">
                          </div>
                        <?php 
                        } 
                        $compteur++;
                        endforeach;?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls<?= $article['ART_ID'] ?>" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon blackCarousel-prev" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls<?= $article['ART_ID'] ?>" role="button" data-slide="next">
                      <span class="carousel-control-next-icon blackCarousel-next" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>
        </div>
        <div class="col-8 border border-dark">
          <div class="card-body">
            <h2 class="card-title text-center"><?= $article['ART_NOM'] ?></h2>
            <p class="card-text font-weight-bold">Marque :</p>
            <p class="card-text " style="font-size: 80%;"><?= $article['ART_MARQUE'] ?></p>
            <p class="card-text font-weight-bold">Description : </p>
              <p class="card-text" style="vertical-align: bottom; font-size: 80%;"><?= $article['ART_DESCRIPTION'] ?></p>
              <div class="bottomBoutique">
              <p class="card-text"><span class="font-weight-bold">Prix:</span> <?= $article['ART_PRIX'] ?>.- CHF &emsp;  <span class="font-weight-bold">Quanité:</span> <?= $article['ART_QTE_STOCK'] ?></p>

                <?php if($modificationPossible){ ?>
                <div class="row">
                  <a href="modifierBoutique?id=<?=$article['ART_ID']?>" class="btn-customLightBlue offset-1">Modifier</a>




                  <?php if($article['ART_STATUT'] == 'ACTIF'){ ?>
                    <span><?= '&emsp;' ?></span>
                    <button name="suppr" type="submit" class="btn-customLightRed" href="article?id=<?= $article['ART_ID'] ?>" role="button">
                    <div class="col-6 d-inline">
                      <span class="text-deco-none">Supprimer</span> 
                    </div>
                    </button>
                    <?php }

                    if($article['ART_STATUT'] != 'ACTIF'){
                      ?>
                                        <span><?= '&emsp;' ?></span>

                    <button name="suppr" type="submit" class="btn-customLightGreen" href="article?id=<?= $article['ART_ID'] ?>"  role="button">
                    <div class="col-6 d-inline">
                      <span class="text-deco-none">Réajouter</span> 
                    </div>
                    </button>
                  <?php }}
                  ?>   
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>    
</div>

<br>
<div class="container text-center" style="margin-bottom: -2%;">
  <a href="boutique" class="btn btn-light border border-dark">Retour à la boutique</a>
</div>


</body>
<br><br><br>
<?php include('includes/footer.php');?>

