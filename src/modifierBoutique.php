<?php 
/** 
 * * Salon de coiffure Eylsée - Victor, Jarod et Maxime
 */

$title = "Elysée - Boutique";
include('includes/header.php');


echo '<script type="text/javascript">';
echo  'window.location(boutique);';
echo '</script>'; 


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

if($modificationPossible){

  if(isset($_POST['change'])){

    if(!empty(getOneArticleID($_GET['id']))){
      $article = getOneArticleID($_GET['id']);

      if(!empty($article)){
      
      $id = $article['ART_ID'];
      if(isset($_POST['inputNom']))
      {
        $nom = $_POST['inputNom'];
      }
      else
      {
        $nom = $article['ART_NOM'];
      }
      
      if(!empty($_POST['inputPrix']))
      {
        $prix = $_POST['inputPrix'];
      }
      else
      {
        $prix = $article['ART_PRIX'];
      }

      if(!empty($_POST['inputMarque']))
      {
        $marque = $_POST['inputMarque'];
      }
      else
      {
        $marque = $article['ART_MARQUE'];
      }

      if(!empty($_POST['inputQte']))
      {
        $qte = $_POST['inputQte'];
      }
      else
      {
        $qte = $article['ART_QTE_STOCK'];
      }

      if(!empty($_POST['inputDescription']))
      {
        $descrip = $_POST['inputDescription'];
      }
      else
      {
        $descrip = $article['ART_DESCRIPTION'];
      }

      if(!empty($_POST['inputCategorie']))
      {
        $categorie = $_POST['inputCategorie'];
      }
      else
      {
        $categorie = $article['ART_CATEGORIE'];
      }
      
      $compteur = 1;
      foreach(getAllImgArticle($article['ART_ID']) as $photo):
        if (!empty($_FILES['monfichier-' . $photo["IMG_ID"]]) AND $_FILES['monfichier-' . $photo["IMG_ID"]]['error'] == 0){
          $infosfichier = pathinfo($_FILES['monfichier-' . $photo["IMG_ID"]]['name']); //path info renvois une array donc on stock l'extension dans exension_upload
          $extension_upload = $infosfichier['extension'];
          $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
          $name = $_POST['inputNom'];
          $file = '' .time(). '-' .$name. '-' . $compteur . '.' .$extension_upload;

            if (in_array($extension_upload, $extensions_autorisees)){ //On verifie que le fichier fait partis des extensions autorisés
              // On peut valider le fichier et le stocker définitivement
              move_uploaded_file($_FILES['monfichier-' . $photo["IMG_ID"]]['tmp_name'], 'Ressources/Boutique/' . $file);

              modifierImageArticle($id, $photo["IMG_ID"],'Ressources/Boutique/' . $file);
            }else{
              ?><script>window.alert("L'extension utilisé n'est pas autorisée, les types d'extensions autorisées sont: 'jpg', 'jpeg', 'gif', 'png'");</script><?php
            }
            $compteur++;
        }
      endforeach;



      /* Suppression des images*/
      foreach(getAllImgArticle($article['ART_ID']) as $photo):
        if(isset($_POST["checkbox-".$photo['IMG_ID']])){
          supprimerImageArticle($_POST["checkbox-".$photo['IMG_ID']]);
        }
      endforeach;


      /* Ajouter des images*/
      for($c = 1; $c <=5; $c++):
        if (!empty($_FILES['monfichierAjouter-' . $c]) AND $_FILES['monfichierAjouter-' . $c]['error'] == 0){
          $infosfichier = pathinfo($_FILES['monfichierAjouter-' . $c]['name']); //path info renvois une array donc on stock l'extension dans exension_upload
          $extension_upload = $infosfichier['extension'];
          $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
          $name = $_POST['inputNom'];
          $file = '' .time(). '-' .$name. '-' . $c . '.' .$extension_upload;
  
            if (in_array($extension_upload, $extensions_autorisees)){ //On verifie que le fichier fait partis des extensions autorisés
              // On peut valider le fichier et le stocker définitivement
              move_uploaded_file($_FILES['monfichierAjouter-' . $c]['tmp_name'], 'Ressources/Boutique/' . $file);
  
              
              ajouterImageArticle($id,'Ressources/Boutique/' . $file);
              
              //A faire
            }
        }
      endfor;
      

      $listeCate = getAllArticleCategorie();
      foreach($listeCate as $cle => $valeur)
      {
        if($_POST['inputCategorie'] == $valeur){
          $existe = 'oui';
        }
      if(isset($existe)){
        modifierUnArticle($id,$nom,$prix,$marque,$qte,$descrip,$categorie);
        echo '<script type="text/javascript">';
        echo  'window.location(boutique);';
        echo '</script>'; 

        }
      }
      $existe = 'non';
    }

  }
}
}

if(isset($existe)){
  echo '<script type="text/javascript">window.location(boutique);</script>';
}

if(isset($_GET['id'])){
$article = getOneArticleID($_GET['id']);}

if ($modificationPossible){?>

    <div class="container">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="AjouterService">Modifier le produit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form id="formModif" action="modifierBoutique?id=<?= $article['ART_ID'] ?>" method="post" enctype="multipart/form-data">
            <br>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <h6>Catégorie : </h6>
                  <div class="form-group">
                  <select id="inputCategorie" name="inputCategorie" class="form-control">
                  <option value="<?=$article['ART_CATEGORIE'];?>"><?= $article['ART_CATEGORIE']; ?></option>

                      <?php foreach(getAllArticleCategorieExceptOne($article['ART_CATEGORIE']) as $donneesCategorie): ?>
                        
                        <option name="<?= $donneesCategorie ?>"><?= $donneesCategorie ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                
                <div class="col-lg-6 col-md-12">
                  <h6>Nom : </h6>
                  <div class="form-group">
                    <input type="text" class="form-control" id="inputNom" name="inputNom" value="<?= $article['ART_NOM'] ?>" placeholder="<?= $article['ART_NOM'] ?>">
                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col-lg-3 col-md-12">
                  <h6>Prix : </h6>
                  <div class="form-group">
                    <input type="number" class="form-control" id="inputPrix" name="inputPrix" value="<?= $article['ART_PRIX'] ?>" placeholder="<?= $article['ART_PRIX'] ?>">
                  </div>
                </div>
                <div class="col-lg-3 col-md-12">
                  <h6>Quantité : </h6>
                  <div class="form-group">
                    <input type="number" class="form-control" id="inputQte" name="inputQte" value="<?= $article['ART_QTE_STOCK'] ?>" placeholder="<?= $article['ART_QTE_STOCK'] ?>">
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <h6>Marque : </h6>
                  <div class="form-group">
                    <input type="text" class="form-control" id="inputMarque" name="inputMarque" value="<?= $article['ART_MARQUE'] ?>" placeholder="<?= $article['ART_MARQUE'] ?>">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <h6>Description : </h6>
                  <div class="form-group">
                    <textarea class="form-control"rows="10" name="inputDescription" id="inputDescription" style="resize: none;"  placeholder="<?= $article['ART_DESCRIPTION'] ?>"><?= $article['ART_DESCRIPTION'] ?></textarea>
                  </div>
                </div>
              </div>

              <?php 
              $compteur = 1;
              foreach(getAllImgArticle($article['ART_ID']) as $photo): ?>
                <div class="row" style="margin-bottom: 1%;">
                  <div class="col-12">
                    <div class="custom-file">
                      <p class="font-weight-bold"><?="Image " . $compteur?></p>
                      <input type="file" id="customFile" name="monfichier-<?=$photo["IMG_ID"]?>" />
                    </div>
                  </div>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="checkbox-<?=$photo['IMG_ID']?>" value="<?=$photo['IMG_ID']?>">
                  <label class="form-check-label" for="inlineCheckbox1">Supprimer</label>
                </div>
                <br><br>
              <?php 
              $compteur++;
              endforeach ?>

              <div>

              <hr>
              <p class="font-weight-bold">Ajouter image</p>
              <div class="col-12" style="margin-top: 2%;">
                <div class="custom-file">
                  <!--<input type="file" name="monfichier2" /><br />-->
                  <input type="file" id="customFile" name="monfichierAjouter-1" onclick="createNewInputFile();" />
                </div>
                <div id="newElementId"></div> 
                
              </div>
            

          <br>
          <div class="modal-footer">
            <a href="boutique"><button type="button" class="btn btn-secondary border border-dark" data-dismiss="modal">Retour</button></a>
            <button type="submit" name="change" class="btn btn-primary">Enregister</button>
          </div>
          </form>
        </div>
      </div>
      </div>
      </div>

      </div>
<br><br><br><br><br>
<?php 
}else{ echo '<script type="text/javascript">window.location.href ="boutique"</script>'; }
include('includes/footer.php'); ?>
