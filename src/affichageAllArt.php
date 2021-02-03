<?php
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
?>

<a href="article?id=<?= $donnees['ART_ID'] ?>">
  <?php if ($modificationPossible){ ?>
    <div class="card col-12 border border-dark cardBoutique" style="min-height: 730px; max-height: 730px;">
  <?php }else{ ?>
    <div class="card col-12 border border-dark cardBoutique" style="min-height: 690px; max-height: 730px;">
  <?php }?>
    <!-- http://placehold.it/700x400 -->
    <div class="card-body" >
      <div id="carouselExampleControls<?= $donnees['ART_ID'] ?>"  class="carousel slide marginauto"  style="height: 400px; width: 350px; object-fit: contain;">
        <div class="carousel-inner">

          <?php 
          $compteur = 0;
          foreach(getAllImgArticle($donnees['ART_ID']) as $photo): 
            if ($compteur == 0){ ?>
              <div class="carousel-item active">
                <img class="d-block w-100" src="<?=$photo["IMG_CHEMIN"]?>" alt="slide" style="height: 400px; width: 350px; object-fit: contain;">
              </div>
            <?php } else{ ?> 
              <div class="carousel-item">
                <img class="d-block w-100" src="<?=$photo["IMG_CHEMIN"]?>" alt="slide" style="height: 400px; width: 350px; object-fit: contain;">
              </div>
            <?php 
            } 
            $compteur++;
            endforeach;?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls<?= $donnees['ART_ID'] ?>" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon blackCarousel-prev" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls<?= $donnees['ART_ID'] ?>" role="button" data-slide="next">
          <span class="carousel-control-next-icon blackCarousel-next" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
        <h4 class="card-text" style="margin-top: 4%;"><?=$donnees['ART_NOM']?></h4>
        <h5 class="card-text"><?=$donnees['ART_CATEGORIE']?></h5>
        <p class="card-text" style="font-size: 70%;"><?=couperMots($donnees['ART_DESCRIPTION'], 140)?></p>
        
        <?php if ($modificationPossible){ ?>
          <div class="bottomBoutique">
        <?php }else{ ?>
          <div class="bottomBoutiquePatronne">
        <?php }?>
          <h4 class="card-text"><?=$donnees['ART_PRIX']?>.-CHF</h4>
          <h5><small class="text-muted">Quantité : <?=$donnees['ART_QTE_STOCK']?></small></h5>
        </div>

      <?php if($modificationPossible){ ?>
      </div>
      <br><br>
        <div class="row">
        <form action="modifierBoutique?id=<?= $donnees['ART_ID'] ?>" method="POST">
        <div class="col-lg-6 d-inline">
        <button class="btn-customLightBlue offset-1" role="button" name="btnmodif" value="btnmodif">
          
            Modifier
          </div>
        
          </button>
          </form>



          <span><?= '&emsp;' ?></span>
        <?php if($donnees['ART_STATUT'] == 'ACTIF'){ ?>

          <a  class="btn-customLightRed" href="boutique?idSupp=<?= $donnees['ART_ID'] ?>" role="button">
          <div class="col-lg-6 d-inline">
            <span class="text-deco-none">Supprimer</span> 
          </div>
          </a>
          <?php }

          if($donnees['ART_STATUT'] != 'ACTIF'){
            ?>
          <a  class="btn-customLightGreen" href="boutique?idSupp=<?= $donnees['ART_ID'] ?>"  role="button">
          <div class="col-lg-6 d-inline">
            <span class="text-deco-none">Réajouter</span> 
          </div>
          </a>
          <?php }}
          ?>               
        
      </div>
  </div>
</a>

