<?php
/**
 ** Projet Elysée - Jarod, Victor et Maxime
 */



if ($_SERVER['SERVER_NAME'] == "esig-sandbox.ch"){
    function myConnection() {
        static $dbc = null;
        if ($dbc == null) {
            try {
                // Sur MAMP (MacOS), l'espace entre mysql: et host= est indispensable...!?!
                $dbc = new PDO('mysql:dbname=hhva_team2020_5;host=hhva.myd.infomaniak.com', 'hhva_team2020_5', 'LBtKn32jx8');
            } catch (PDOException $e) {
                header("Location:error?message=".$e->getMessage());
            }
    }
        return $dbc;
    }
}else{
    function myConnection() {
        static $dbc = null;
        if ($dbc == null) {
            try { 
                $dbc = new PDO('mysql:dbname=hhva_team2020_5;host=localhost', 'root', '');
            } catch (PDOException $e) {
                header("Location:error?message=".$e->getMessage());
            }
        }
        return $dbc;
    }
}


/**
 * ! Page boutique
 */

function getAllArticleInactif()
{
        try {
            $request = myConnection()->prepare("SELECT * FROM ely_article where art_statut <> 'ACTIF'");
            $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
        return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllArticleActif()
{
        try {
            $request = myConnection()->prepare("SELECT * FROM ely_article where art_statut ='ACTIF'");
            $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
        return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllArticleActifSearch($search)
{
        try {
            $request = myConnection()->prepare("SELECT * FROM ely_article WHERE art_statut = 'ACTIF' and art_nom like '%$search%' and art_statut ='ACTIF'");
            $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
        return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllArticleCategorie()
{
        return $categorieListe = [0 => 'Teinture', 1 => 'Shampooing', 2 =>'Soin des cheveux'];
}

function getAllArticleCategorieExceptOne($categorie)
{
        $categorieListe = getAllArticleCategorie();
        foreach($categorieListe as $cle => $valeur)
        {
            if($valeur == $categorie)
            {
                $pos = array_search($categorie, $categorieListe);
                unset($categorieListe[$pos]);
            }
        }
        return $categorieListe;
}

function getFirstImgArticle($id)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM `elv_image` WHERE `ART_ID` = :id limit 1");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getAllImgArticle($id)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM `elv_image` WHERE `ART_ID` = :id");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}


function getAllArticleWhereCategorieIs($categorie)
{
        try {
            $request = myConnection()->prepare("SELECT * FROM ely_article WHERE art_categorie = :categorie");
            $request->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
        return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllArticleWhereCategorieAndNameAre($categorie,$search)
{
        try {
            $request = myConnection()->prepare("SELECT * FROM ely_article WHERE art_categorie = :categorie and art_nom like '%$search%' and art_statut ='ACTIF'");
            $request->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
        return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getOneArticleID($id)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_article WHERE art_id = :id ");
        $request->bindParam(':id', $id, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function suppressionLogiqueArticle($id)
{
    $article = getOneArticleID($id);
    try {
        switch($article['ART_STATUT']){
            default:
            case "ACTIF":
                $changement = "INACTIF";
            break;

            case $article['ART_STATUT'] != "ACTIF":
                $changement = "ACTIF";
            break;
        }
        $request = myConnection()->prepare("UPDATE ely_article SET art_statut = :changement WHERE art_id = :id ");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->bindParam(':changement', $changement, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function addProduit($nom, $prix, $marque, $qte, $description, $categorie ){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_article (art_nom, art_prix, art_marque, art_qte_stock ,art_description, art_categorie)
        VALUES(:art_nom,:art_prix, :art_marque, :art_qte_stock, :art_description, :art_categorie)");
        $request->bindParam(':art_nom', $nom, PDO::PARAM_STR);
        $request->bindParam(':art_prix', $prix, PDO::PARAM_INT);
        $request->bindParam(':art_marque', $marque, PDO::PARAM_STR);
        $request->bindParam(':art_qte_stock', $qte, PDO::PARAM_INT);
        $request->bindParam(':art_description', $description, PDO::PARAM_STR);
        $request->bindParam(':art_categorie', $categorie, PDO::PARAM_STR);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function modifierUnArticle($id,$nom,$prix,$marque,$qte,$descrip,$categorie)
{
    try {
        $request = myConnection()->prepare("UPDATE ely_article SET art_nom = :nom, art_prix = :prix, art_marque = :marque,
         art_qte_stock = :qte,art_description = :descrip, art_categorie = :categorie WHERE art_id = :id ");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->bindParam(':nom', $nom, PDO::PARAM_STR);
        $request->bindParam(':prix', $prix, PDO::PARAM_INT);
        $request->bindParam(':marque', $marque, PDO::PARAM_STR);
        $request->bindParam(':qte', $qte, PDO::PARAM_INT);
        $request->bindParam(':descrip', $descrip, PDO::PARAM_STR);
        $request->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function ajouterImageArticle($artid,$chemin)
{
    try{
        $request = myConnection()->prepare
        ("INSERT INTO elv_image(art_id, img_chemin) VALUES (:artid, :chemin)");
        $request->bindParam(':artid', $artid, PDO::PARAM_INT);
        $request->bindParam(':chemin', $chemin, PDO::PARAM_STR);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function modifierImageArticle($art_id, $img_id, $chemin)
{
    try{
        $request = myConnection()->prepare
        ("UPDATE elv_image SET img_chemin = :img_chemin WHERE art_id = :art_id AND img_id = :img_id");
        $request->bindParam(':art_id', $art_id, PDO::PARAM_INT);
        $request->bindParam(':img_id', $img_id, PDO::PARAM_INT);
        $request->bindParam(':img_chemin', $chemin, PDO::PARAM_STR);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function supprimerImageArticle($img_id){
    try{
        $request = myConnection()->prepare
        ("DELETE FROM elv_image WHERE img_id = :img_id");
        $request->bindParam(':img_id', $img_id, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getLastArticle(){
    try {
        $request = myConnection()->prepare("SELECT max(art_id) as maximum FROM ely_article");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getLastCoiffeur(){
    try {
        $request = myConnection()->prepare("SELECT max(coi_id) as maximum FROM ely_coiffeur");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}


function getLastClient(){
    try {
        $request = myConnection()->prepare("SELECT max(cli_id) as maximum FROM ely_client");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getLastRendezVous(){
    try {
        $request = myConnection()->prepare("SELECT max(rdv_id) as maximum FROM ely_rendezvous");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * ! Page Acceuil
 */

function insertImage($IMG_NAME , $IMG_CHEMIN ){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO elv_image (img_name,img_chemin )
        VALUES(:IMG_NAME,:IMG_CHEMIN)");
        $request->bindParam(':IMG_NAME', $IMG_NAME, PDO::PARAM_STR);
        $request->bindParam(':IMG_CHEMIN', $IMG_CHEMIN, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getAllImageAcceuil(){
    try{
        $request = myConnection()->prepare("SELECT * FROM elv_image WHERE ART_ID is null ");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}
function supprimerImageAcceuil($img_id){
    try{
        $request = myConnection()->prepare
        ("DELETE FROM elv_image WHERE img_id = :img_id AND ART_ID IS NULL");
        $request->bindParam(':img_id', $img_id, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

/**
 * ! Page liste des prix
 */

function getAllCoupePrestation($typePrestation)
{
        try {
            $request = myConnection()->prepare("SELECT * FROM ely_service WHERE ser_type = :typePrestation and ser_statut = 'ACTIF'");
            $request->bindParam(':typePrestation', $typePrestation, PDO::PARAM_STR);
            $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
        return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllCoupePrestation2()
{
        try {
            $request = myConnection()->prepare("SELECT * FROM ely_service WHERE ser_statut = 'ACTIF'");
            $request->bindParam(':typePrestation', $typePrestation, PDO::PARAM_STR);
            $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
        return $request->fetchAll(PDO::FETCH_ASSOC);
}

function addService($type, $nom, $prix, $time, $description){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_service (ser_nom, ser_type, ser_prix, ser_description, ser_temps_estimation)
        VALUES(:ser_nom,:ser_type,:ser_prix,:ser_description,:ser_temps_estimation)");
        $request->bindParam(':ser_nom', $nom, PDO::PARAM_STR);
        $request->bindParam(':ser_type', $type, PDO::PARAM_STR);
        $request->bindParam(':ser_prix', $prix, PDO::PARAM_INT);
        $request->bindParam(':ser_description', $description, PDO::PARAM_STR);
        $request->bindParam(':ser_temps_estimation', $time, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getAllCoiffeur(){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur where coi_statut = 'ACTIF'");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllCoiffeurDesc(){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur where coi_statut = 'ACTIF' ORDER BY COI_ID DESC");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllCoiffeurRowCount(){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur where coi_statut = 'ACTIF'");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
}

function getCoiffeurId($coi_id){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur where coi_id = :coi_id");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getAllCoiffeurInactif()
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur where coi_statut != 'ACTIF'");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPosteCoiffeur()
{
    return ['0' => 'Coiffeur', '1' => 'Coiffeur superieur', '2'=>'Patronne'];
}

function getAllPosteCoiffeurExceptOne($poste)
{
    $posteListe = getAllPosteCoiffeur();
    $pos = array_search($poste, $posteListe);
    unset($posteListe[$pos]);
    return $posteListe;
}



function modifierUnCoiffeurForCoiffeur($name,$prenom,$mail,$mdp,$num,$id)
{   
    try {
    $request = myConnection()->prepare("UPDATE ely_coiffeur SET COI_NOM = :name, COI_PRENOM = :prenom, COI_MAIL = :mail, COI_MDP = :mdp,
    COI_NUMTEL = :num  WHERE COI_ID = :id");

    $request->bindParam(':name', $name, PDO::PARAM_STR);
    $request->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $request->bindParam(':mail', $mail, PDO::PARAM_STR);
    $request->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $request->bindParam(':num', $num, PDO::PARAM_INT);
    $request->bindParam(':id', $id, PDO::PARAM_INT);
    $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function modifierUnCoiffeurForPatronne($name,$prenom,$mail,$num,$poste,$id)
{   
    try {
    $request = myConnection()->prepare("UPDATE ely_coiffeur SET COI_NOM = :name, COI_PRENOM = :prenom, COI_MAIL = :mail,
    COI_NUMTEL = :num, COI_POSTE = :poste WHERE COI_ID = :id");

    $request->bindParam(':name', $name, PDO::PARAM_STR);
    $request->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $request->bindParam(':mail', $mail, PDO::PARAM_STR);
    $request->bindParam(':num', $num, PDO::PARAM_INT);
    $request->bindParam(':poste', $poste, PDO::PARAM_STR);
    $request->bindParam(':id', $id, PDO::PARAM_INT);
    $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function AjouterunCoiffeur($nom, $prenom, $mail,$mdp,$num,$poste){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_coiffeur (COI_NOM,COI_PRENOM,COI_MAIL,COI_MDP,COI_NUMTEL,COI_POSTE)
        VALUES(:nom,:prenom, :mail, :mdp, :num,:poste)");
        $request->bindParam(':nom', $nom, PDO::PARAM_STR);
        $request->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $request->bindParam(':mail', $mail, PDO::PARAM_STR);
        $request->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $request->bindParam(':num', $num, PDO::PARAM_STR);
        $request->bindParam(':poste', $poste, PDO::PARAM_STR);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function InsererHoraire($id,$jour, $HeureDebutM, $HeureFinM,$HeureDebutA,$HeureFinA){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_trancheshoraires (coi_id,tra_jour,tra_heureDebutMatin ,tra_heureFinMatin ,tra_heureDebutAprem,tra_heureFinAprem)
        VALUES(:id,:jour,:HeureDebutM, :HeureFinM, :HeureDebutA, :HeureFinA)");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->bindParam(':jour', $jour, PDO::PARAM_STR);
        $request->bindParam(':HeureDebutM', $HeureDebutM);
        $request->bindParam(':HeureFinM', $HeureFinM);
        $request->bindParam(':HeureDebutA', $HeureDebutA);
        $request->bindParam(':HeureFinA', $HeureFinA);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function UpdateHoraire($id,$jour,$HeureDebutM, $HeureFinM,$HeureDebutA,$HeureFinS){
    try{
        $request = myConnection()->prepare
        ("UPDATE ely_trancheshoraires SET tra_heureDebutMatin =:HeureDebutM ,tra_heureFinMatin = :HeureFinM,tra_heureDebutAprem = :HeureDebutA,tra_heureFinAprem = :HeureFinS WHERE tra_jour = :jour AND coi_id = :id ");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->bindParam(':jour', $jour);
        $request->bindParam(':HeureDebutM', $HeureDebutM);
        $request->bindParam(':HeureFinM', $HeureFinM);
        $request->bindParam(':HeureDebutA', $HeureDebutA);
        $request->bindParam(':HeureFinS', $HeureFinS);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}


function GetAllHoraireWhereCoiId($COI_ID){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_trancheshoraires  WHERE COI_ID = :COI_ID order by tra_id");
        $request->bindParam(':COI_ID', $COI_ID, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
} 



function changementLogiqueCoiffeur($id)
{
    $coiffeur = getOneCoiffeurID($id);
    try {
        switch($coiffeur['COI_STATUT']){
            default:
            case "ACTIF":
                $changement = "INACTIF";
            break;

            case "INACTIF":
                $changement = "ACTIF";
            break;
        }
    $request = myConnection()->prepare("UPDATE ely_coiffeur SET COI_STATUT = :statut WHERE COI_ID = :id");
    $request->bindParam(':id', $id, PDO::PARAM_INT);
    $request->bindParam(':statut', $changement, PDO::PARAM_STR);
    $request->execute();
    } catch (PDOException $e) {
    header("Location:error?message=".$e->getMessage());
    }
}

function Suppresiondefinitivecoiffeur($id)
{
    try {
    $request = myConnection()->prepare("DELETE FROM ely_coiffeur WHERE COI_ID = :id");
    $request->bindParam(':id', $id, PDO::PARAM_INT);
    $request->execute();
    } 
    catch (PDOException $e) {
    header("Location:error?message=".$e->getMessage());
    }
}

function modifierPhotoCoiffeur($id,$file)
{
    try {
        $request = myConnection()->prepare("UPDATE ely_coiffeur SET COI_PHOTO = :photo WHERE COI_ID = :id");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->bindParam(':photo', $file, PDO::PARAM_STR);
        $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
}

function canCoiffeurDoThisService($idcoi,$idser)
{
    try {
        $request = myConnection()->prepare
        ("SELECT * FROM ely_coiffeur inner join service_coiffeur on
         ely_coiffeur.coi_id = service_coiffeur.coi_id 
         where ely_coiffeur.coi_id = :idcoi and service_coiffeur.ser_id = :idser");
        $request->bindParam(':idcoi', $idcoi, PDO::PARAM_INT);
        $request->bindParam(':idser', $idser, PDO::PARAM_INT);

        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function addService_Coiffeur($idCoiffeur, $idService){
    try{
        $request = myConnection()->prepare("INSERT INTO service_coiffeur (coi_id, ser_id)
        VALUES(:coi_id,:ser_id)");
        $request->bindParam(':coi_id', $idCoiffeur, PDO::PARAM_INT);
        $request->bindParam(':ser_id', $idService, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function deleteService_Coiffeur($idCoiffeur, $idService){
    try{
        $request = myConnection()->prepare("DELETE FROM service_coiffeur where coi_id = :idcoi and ser_id = :idser");
        $request->bindParam(':idcoi', $idCoiffeur, PDO::PARAM_INT);
        $request->bindParam(':idser', $idService, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getLastService(){
    try {
        $request = myConnection()->prepare("SELECT max(ser_id) as maximum FROM ely_service");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getAllService(){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_service");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}


function getAllServiceSelonCoiffeur($coi_id){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_service INNER JOIN service_coiffeur ON ely_service.ser_id = service_coiffeur.ser_id WHERE service_coiffeur.coi_id = :coi_id AND ely_service.ser_statut = 'ACTIF'");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getOneService($idser)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_service where ser_id = :id");
        $request->bindParam(':id', $idser, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}



function getAllServiceCategorie()
{
        return $categorieListe = [0 => 'Coupe', 1 => 'Beaute des cheveux', 2 =>'Soin', 3 =>'Autre'];
}

function getAllServiceCategorieExceptOne($categorie)
{
        $categorieListe = getAllServiceCategorie();
        $pos = array_search($categorie, $categorieListe);
        unset($categorieListe[$pos]);
        return $categorieListe;
}

function updateService($idService, $type, $nom, $prix, $duree, $description)
{
    $request = myConnection()->prepare("UPDATE ely_service SET ser_nom = :ser_nom, ser_type = :ser_type, ser_prix = :ser_prix, ser_description = :ser_description, ser_temps_estimation = :ser_temps_estimation 
    WHERE ser_id = :ser_id");
    $request->bindParam(':ser_nom', $nom, PDO::PARAM_STR);
    $request->bindParam(':ser_type', $type, PDO::PARAM_STR);
    $request->bindParam(':ser_prix', $prix, PDO::PARAM_INT);
    $request->bindParam(':ser_description', $description, PDO::PARAM_STR);
    $request->bindParam(':ser_temps_estimation', $duree, PDO::PARAM_INT);
    $request->bindParam(':ser_id', $idService, PDO::PARAM_INT);
    $request->execute();
}

function supprimerService($idService){
    $request = myConnection()->prepare("UPDATE ely_service SET ser_statut = 'INACTIF' WHERE ser_id = :ser_id");
    $request->bindParam(':ser_id', $idService, PDO::PARAM_INT);
    $request->execute();
}




function getAllCoiffeurWhereMailAndMdp($mailconnection,$mdpconnection)
{
    try 
    {
        $request=myConnection()->prepare("SELECT * FROM ely_coiffeur WHERE coi_mail= :mailconnection AND coi_mdp= :mdpconnection AND coi_statut= 'ACTIF'");
        $request->bindParam(':mailconnection', $mailconnection, PDO::PARAM_STR);
        $request->bindParam(':mdpconnection', $mdpconnection, PDO::PARAM_STR);
        $request->execute();
    } catch (Exception $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}


function getAllCoiffeurWhereMailAndTel($mailconnection,$telnum)
{
    try 
    {
        $request=myConnection()->prepare("SELECT * FROM ely_coiffeur WHERE coi_mail= :mailconnection OR coi_numtel= :telnum");
        $request->bindParam(':mailconnection', $mailconnection, PDO::PARAM_STR);
        $request->bindParam(':telnum', $telnum, PDO::PARAM_STR);
        $request->execute();
    } catch (Exception $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function getAllServiceExceptOne($ser_id)
{
    try
    {
    $request = myConnection()->prepare("SELECT * FROM ely_service WHERE ser_id != :ser_id1");
    $request->bindParam(':ser_id1',$ser_id, PDO::PARAM_INT);
    $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);  
}

function getAllServiceExceptTwo($ser_id,$ser_id2)
{
    try
    {
        
    $request = myConnection()->prepare("SELECT * FROM ely_service WHERE ser_id not in (select ser_id from ely_service where ser_id = :ser_id1 or ser_id = :ser_id2)");
    $request->bindParam(':ser_id1',$ser_id, PDO::PARAM_INT);
    $request->bindParam(':ser_id2',$ser_id2, PDO::PARAM_INT);
    $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);  
}

function getAllServiceExceptThree($ser_id,$ser_id2,$ser_id3)
{
    try
    {
    $request = myConnection()->prepare("SELECT * FROM ely_service WHERE ser_id not in
    (select ser_id from ely_service where ser_id = :ser_id1 or ser_id = :ser_id2 or ser_id = :ser_id3)");
    $request->bindParam(':ser_id1',$ser_id, PDO::PARAM_INT);
    $request->bindParam(':ser_id2',$ser_id2, PDO::PARAM_INT);
    $request->bindParam(':ser_id3',$ser_id3, PDO::PARAM_INT);
    $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);  
}

function getEveryCoiffeursThatCanDoOneOfThose2Services($ser_id1,$ser_id2)
{
    try
    {
    $request = myConnection()->prepare("SELECT * FROM ely_coiffeur 
    INNER JOIN service_coiffeur ON ely_coiffeur.coi_id = service_coiffeur.coi_id 
    INNER JOIN ely_service ON service_coiffeur.ser_id = ely_service.ser_id
    WHERE service_coiffeur.ser_id = :ser_id1 or service_coiffeur.ser_id = :ser_id2");
    $request->bindParam(':ser_id1',$ser_id1, PDO::PARAM_INT);
    $request->bindParam(':ser_id2',$ser_id2, PDO::PARAM_INT);
    $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);  
}

function getEveryCoiffeursThatCanDoOneOfThose3Services($ser_id1,$ser_id2,$ser_id3)
{
    try
    {
    $request = myConnection()->prepare("SELECT ely_coiffeur.COI_NOM, ely_coiffeur.COI_ID FROM ely_coiffeur 
    INNER JOIN service_coiffeur ON ely_coiffeur.coi_id = service_coiffeur.coi_id 
    INNER JOIN ely_service ON service_coiffeur.ser_id = ely_service.ser_id
    WHERE service_coiffeur.ser_id = :ser_id1 or service_coiffeur.ser_id =  :ser_id2 or service_coiffeur.ser_id = :ser_id3");
    $request->bindParam(':ser_id1',$ser_id1, PDO::PARAM_INT);
    $request->bindParam(':ser_id2',$ser_id2, PDO::PARAM_INT);
    $request->bindParam(':ser_id3',$ser_id3, PDO::PARAM_INT);
    $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);  
    
}

function getCoiffeurService($ser_id){
    try {
        $request = myConnection()->prepare("SELECT * FROM service_coiffeur 
        INNER JOIN ely_service ON service_coiffeur.ser_id = ely_service.ser_id 
        INNER JOIN ely_coiffeur ON service_coiffeur.coi_id = ely_coiffeur.coi_id
        WHERE service_coiffeur.ser_id = :ser_id");
        $request->bindParam(':ser_id',$ser_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);              
 }

 function getCoiffeurid_nameWhereMail($mail){
    try {
        $request = myConnection()->prepare("SELECT * FROM  ely_coiffeur WHERE coi_mail = :mail");
        $request->bindParam(':mail',$mail, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;               
 }

 function getCoiffeuRecupidWhereMail($mail){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_recupmdp WHERE mail= :mail");
        $request->bindParam(':mail',$mail, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;               
 }

function UpdateCoiffeurRecupMdp($code,$mail){
    try 
    {
        $request =myConnection()->prepare("UPDATE ely_recupmdp SET code= :code WHERE mail= :mail ");
        $request->bindParam(':code',$code, PDO::PARAM_INT);
        $request->bindParam(':mail',$mail, PDO::PARAM_STR);
        $request->execute();
    } 
    catch (PDOException $e) 
    {
    header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function InsertCoiffeurRecupMdp($code,$mail){
    try 
    {
        $request =myConnection()->prepare("INSERT INTO ely_recupmdp (code,mail) VALUES(:code,:mail) ");
        $request->bindParam(':code',$code, PDO::PARAM_INT);
        $request->bindParam(':mail',$mail, PDO::PARAM_STR);
        $request->execute();
    } 
    catch (PDOException $e) 
    {
    header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function getAllCoiffeuridWhereMailAndcode($mail,$code)
{
    try 
    {
        $request=myConnection()->prepare("SELECT * FROM ely_recupmdp WHERE mail = :mail AND code = :code");
        $request->bindParam(':mail', $mail, PDO::PARAM_STR);
        $request->bindParam(':code', $code, PDO::PARAM_INT);
        $request->execute();
    } catch (Exception $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function updatecoiffeurRecupmdpstatut($mail)
{
    try 
    {
        $request=myConnection()->prepare("UPDATE ely_recupmdp SET confirme=1 WHERE mail= :mail "); 
        $request->bindParam(':mail', $mail, PDO::PARAM_STR);
        $request->execute();
    } catch (Exception $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function verifierSiRecuperateurConfirme($mail){
    try 
    {
        $request=myConnection()->prepare("SELECT confirme FROM  ely_recupmdp WHERE mail= :mail"); 
        $request->bindParam(':mail', $mail, PDO::PARAM_STR);
        $request->execute();
    } catch (Exception $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function UpdateCoiffeurMdp($mdp,$mail)
{
    try 
    {
        $request=myConnection()->prepare("UPDATE ely_coiffeur SET coi_mdp = :mdp WHERE coi_mail = :mail");
        $request->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $request->bindParam(':mail', $mail, PDO::PARAM_STR);
        $request->execute();
    } catch (Exception $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function DeleteCoi_WhoRecup($mail){
    try
    {
        $request=myConnection()->prepare("DELETE FROM ely_recupmdp WHERE mail= :mail ");
        $request->bindParam(':mail', $mail, PDO::PARAM_STR);
        $request->execute();
    }
    catch(Exception $e){
       header("Location:error?message=".$e->getMessage());
    }
    return $request;
}
/**
 * ! Page 
 */

function getAllCoiffeurService($idService){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur INNER JOIN service_coiffeur ON service_coiffeur.coi_id = ely_coiffeur.coi_id WHERE ser_id = :ser_id");
        $request->bindParam(':ser_id', $idService, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * ! Page Parcours
 */

function getOneCoiffeur($mail)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur where coi_mail = :mail");
        $request -> bindParam(':mail',$mail,PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getOneCoiffeurID($id)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur where coi_id = :id");
        $request -> bindParam(':id',$id,PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}


function getPremierCoiffeurID(){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur LIMIT 1");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getDernierCoiffeurID(){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur ORDER BY coi_id DESC LIMIT 1");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getPremierCoiffeur($coi_id){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_coiffeur WHERE coi_id = :coi_id");
        $request -> bindParam(':coi_id',$coi_id,PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}


function getAllParagraheCoiffeur($idCoiffeur){
    try{
        $request = myConnection()->prepare("SELECT * FROM ely_para_descrip WHERE coi_id = :coi_id AND des_statut = 'ACTIF' ORDER BY des_ordre");
            $request->bindParam(':coi_id', $idCoiffeur, PDO::PARAM_STR);
            $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllDiplomeCoiffeur($idCoiffeur){
    try{
        $request = myConnection()->prepare("SELECT * FROM ely_diplome WHERE coi_id = :coi_id AND dip_statut = 'ACTIF' ORDER BY dip_date_obtention DESC");
            $request->bindParam(':coi_id', $idCoiffeur, PDO::PARAM_INT);
            $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}


function insertParagraphe($coi_i, $des_titre, $des_texte, $des_ordre){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_para_descrip (coi_id, des_titre, des_texte, des_ordre)
        VALUES(:coi_id,:des_titre,:des_texte,:des_ordre)");
        $request->bindParam(':coi_id', $coi_i, PDO::PARAM_INT);
        $request->bindParam(':des_titre', $des_titre, PDO::PARAM_STR);
        $request->bindParam(':des_texte', $des_texte, PDO::PARAM_STR);
        $request->bindParam(':des_ordre', $des_ordre, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getNbParagraphe($coi_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_para_descrip WHERE coi_id = :coi_id AND des_statut = 'ACTIF'");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function getNbCoiffeur(){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_coiffeur");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function getNBDiplome($COI_ID){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_diplome WHERE coi_id = :coi_id AND dip_statut = 'ACTIF'");
        $request->bindParam(':coi_id', $COI_ID, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}


function updateParagraphe($paragrapheID, $des_titre, $des_texte, $des_ordre){
        try{
            $request = myConnection()->prepare("UPDATE ely_para_descrip SET des_titre = :des_titre, des_texte = :des_texte, des_ordre = :des_ordre WHERE des_id = :des_id ");
            $request->bindParam(':des_titre', $des_titre, PDO::PARAM_STR);
            $request->bindParam(':des_texte', $des_texte, PDO::PARAM_STR);
            $request->bindParam(':des_ordre', $des_ordre, PDO::PARAM_INT);
            $request->bindParam(':des_id', $paragrapheID, PDO::PARAM_INT);
            $request->execute();
        } catch (PDOException $e) {
            header("Location:error?message=".$e->getMessage());
        }
}

function updateParagrapheSansOrdre($paragrapheID, $des_titre, $des_texte){
    try{
        $request = myConnection()->prepare("UPDATE ely_para_descrip SET des_titre = :des_titre, des_texte = :des_texte WHERE des_id = :des_id ");
        $request->bindParam(':des_titre', $des_titre, PDO::PARAM_STR);
        $request->bindParam(':des_texte', $des_texte, PDO::PARAM_STR);
        $request->bindParam(':des_id', $paragrapheID, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getParagrapheOrdre($ORDRE){
    try{
        $request = myConnection()->prepare("SELECT * FROM ely_para_descrip WHERE des_ordre = :des_ordre");
        $request->bindParam(':des_ordre', $ORDRE, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getParagrapheOrdreAvecID($coi_id, $des_ordre){
    try{
        $request = myConnection()->prepare("SELECT * FROM ely_para_descrip WHERE des_ordre = :des_ordre AND coi_id = :coi_id");
        $request->bindParam(':des_ordre', $des_ordre, PDO::PARAM_INT);
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}


function getParagraphe($id){
    try{
        $request = myConnection()->prepare("SELECT * FROM ely_para_descrip WHERE des_id = :des_id");
        $request->bindParam(':des_id', $id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getParagraphePlusGrandQueXOrdre($coi_id, $des_ordre){
    try{
        $request = myConnection()->prepare("SELECT * FROM ely_para_descrip WHERE coi_id = :coi_id AND des_ordre > :des_ordre");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->bindParam(':des_ordre', $des_ordre, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function updateParagrapheOrdre($des_id, $des_ordre){
    try{
        $request = myConnection()->prepare("UPDATE ely_para_descrip SET des_ordre = :des_ordre WHERE des_id = :des_id ");
        $request->bindParam(':des_ordre', $des_ordre, PDO::PARAM_INT);
        $request->bindParam(':des_id', $des_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function deleteParagraphe($des_id){
    try{
        $request = myConnection()->prepare("DELETE FROM ely_para_descrip WHERE des_id = :des_id");
        $request->bindParam(':des_id', $des_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function insertDiplome($coi_id, $dip_nom, $dip_date_obtention, $dip_photo){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_diplome (coi_id, dip_nom, dip_date_obtention, dip_photo)
        VALUES(:coi_id,:dip_nom,:dip_date_obtention,:dip_photo)");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->bindParam(':dip_nom', $dip_nom, PDO::PARAM_STR);
        $request->bindParam(':dip_date_obtention', $dip_date_obtention, PDO::PARAM_STR);
        $request->bindParam(':dip_photo', $dip_photo, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function deleteDiplome($dip_id){
    try{
        $request = myConnection()->prepare("UPDATE ely_diplome SET dip_statut = 'INACTIF' WHERE dip_id = :dip_id");
        $request->bindParam(':dip_id', $dip_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function couperMots($mots, $longueur){
    foreach (range(0, $longueur ) as $position) {
        if (strlen($mots) > $position) {
            echo $mots[$position];
        }
    }
    if(strlen($mots) > $longueur)
    {
        echo '...';
    }
}


/*Liste des clients */

function getAllClient()
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_client WHERE cli_statut = 'ACTIF'");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getAllClientPourCoiffeur($coi_id)
{
    try {
        $request = myConnection()->prepare("SELECT DISTINCT ely_client.cli_id, ely_client.cli_nom, ely_client.cli_prenom
        FROM ely_client INNER JOIN ely_rendezvous ON ely_client.cli_id = ely_rendezvous.cli_id WHERE rdv_statut = 'ACTIF' AND ely_rendezvous.coi_id = :coi_id");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getClient($cli_id)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_client WHERE cli_id = :cli_id");
        $request->bindParam(':cli_id', $cli_id, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}



function addClient($cli_nom, $cli_prenom, $cli_tel, $cli_email){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_client (cli_nom, cli_prenom, cli_tel, cli_email)
        VALUES(:cli_nom,:cli_prenom, :cli_tel, :cli_email)");
        $request->bindParam(':cli_nom', $cli_nom, PDO::PARAM_STR);
        $request->bindParam(':cli_prenom', $cli_prenom, PDO::PARAM_STR);
        $request->bindParam(':cli_tel', $cli_tel, PDO::PARAM_STR);
        $request->bindParam(':cli_email', $cli_email, PDO::PARAM_STR);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getNbClientTel($cli_tel){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client WHERE cli_tel = :cli_tel");
        $request->bindParam(':cli_tel', $cli_tel, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
}

function getClientTel($cli_tel){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client WHERE cli_tel = :cli_tel");
        $request->bindParam(':cli_tel', $cli_tel, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getNbClientEmail($cli_email){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client WHERE cli_email = :cli_email AND cli_email is not null");
        $request->bindParam(':cli_email', $cli_email, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
    
}

function deleteClient($cli_id){
    try{
        $request = myConnection()->prepare("UPDATE ely_client SET cli_statut = 'INACTIF' WHERE cli_id = :cli_id");
        $request->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function editClient($cli_nom, $cli_prenom, $cli_email, $cli_tel, $cli_description, $cli_id){
    try {
        $request = myConnection()->prepare("UPDATE ely_client SET cli_nom = :cli_nom, cli_prenom = :cli_prenom, cli_email = :cli_email, cli_tel = :cli_tel,cli_description = :cli_description WHERE cli_id = :cli_id");
        $request->bindParam(':cli_nom', $cli_nom, PDO::PARAM_STR);
        $request->bindParam(':cli_prenom', $cli_prenom, PDO::PARAM_STR);
        $request->bindParam(':cli_email', $cli_email, PDO::PARAM_STR);
        $request->bindParam(':cli_tel', $cli_tel, PDO::PARAM_STR);
        $request->bindParam(':cli_description', $cli_description, PDO::PARAM_STR);
        $request->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function editClientCommentaire($cli_description, $cli_id){
    try {
        $request = myConnection()->prepare("UPDATE ely_client SET cli_description = :cli_description WHERE cli_id = :cli_id");
        $request->bindParam(':cli_description', $cli_description, PDO::PARAM_STR);
        $request->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getClientInformationRdv($rdv_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_rendezvous
		INNER JOIN rdv_periode ON ely_rendezvous.RDV_ID = rdv_periode.RDV_ID		
		INNER JOIN ely_periode ON rdv_periode.PER_ID = ely_periode.PER_ID
        INNER JOIN ely_client ON ely_client.cli_id = ely_rendezvous.cli_id 
        INNER JOIN rdv_service ON ely_rendezvous.rdv_id = rdv_service.rdv_id 
        INNER JOIN ely_service ON rdv_service.ser_id = ely_service.ser_id 
        INNER JOIN ely_coiffeur ON ely_rendezvous.COI_ID = ely_coiffeur.COI_ID
        WHERE ely_rendezvous.rdv_id = :rdv_id AND ely_rendezvous.rdv_statut = 'ACTIF'
        ");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function deleteAServiceAboutARdv($ser_id,$rdv_id)
{
    try{
        $request = myConnection()->prepare
        ("DELETE FROM rdv_service WHERE ser_id = :ser_id AND rdv_id = :rdv_id LIMIT 1");
        $request->bindParam(':ser_id', $ser_id, PDO::PARAM_INT);
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function insertAServiceAboutARdv($ser_id,$rdv_id)
{
    try{
        $request = myConnection()->prepare
        ("INSERT INTO rdv_service(ser_id, rdv_id) VALUES (:ser_id,:rdv_id)");
        $request->bindParam(':ser_id', $ser_id, PDO::PARAM_INT);
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getClientInformationRdvVoirRDV($rdv_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_rendezvous 
        INNER JOIN ely_coiffeur ON ely_rendezvous.coi_id = ely_coiffeur.coi_id 
        INNER JOIN rdv_periode ON rdv_periode.rdv_id = ely_rendezvous.rdv_id
        INNER JOIN ely_periode ON ely_periode.per_id = rdv_periode.per_id
        WHERE ely_rendezvous.rdv_id = :rdv_id
        ");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getRdvClient($cli_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_rendezvous WHERE cli_id = :cli_id AND rdv_statut = 'ACTIF'");
        $request->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getRdv($rdv_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_rendezvous WHERE rdv_id = :rdv_id AND rdv_statut = 'ACTIF'");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}


function getNbRDVClient($cli_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_rendezvous WHERE cli_id = :cli_id AND rdv_statut = 'ACTIF'");
        $request->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
    
}

function editRendezvous($rdv_id, $coi_id, $rdv_description, $rdv_prix){
    try{
        $request = myConnection()->prepare("UPDATE ely_rendezvous SET coi_id = :coi_id, rdv_description = :rdv_description, rdv_prix = :rdv_prix WHERE rdv_id = :rdv_id");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->bindParam(':rdv_description', $rdv_description, PDO::PARAM_STR);
        $request->bindParam(':rdv_prix', $rdv_prix, PDO::PARAM_INT);
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function editRendezvousPourCoiffeur($rdv_id, $rdv_description, $rdv_prix){
    try{
        $request = myConnection()->prepare("UPDATE ely_rendezvous SET rdv_description = :rdv_description, rdv_prix = :rdv_prix WHERE rdv_id = :rdv_id");
        $request->bindParam(':rdv_description', $rdv_description, PDO::PARAM_STR);
        $request->bindParam(':rdv_prix', $rdv_prix, PDO::PARAM_INT);
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function editRendezvousTypeDePrestation($rdv_id, $ser_id){
    try{
        $request = myConnection()->prepare("UPDATE rdv_service SET ser_id = :ser_id WHERE rdv_id = :rdv_id");
        $request->bindParam(':ser_id', $ser_id, PDO::PARAM_INT);
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}


function getIdCoiffeurNomPrenom($coi_nom, $coi_prenom){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_coiffeur WHERE coi_nom = :coi_nom AND coi_prenom = :coi_prenom");
        $request->bindParam(':coi_nom', $coi_nom, PDO::PARAM_STR);
        $request->bindParam(':coi_prenom', $coi_prenom, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function deleteRDV($rdv_id){
    try{
        $request = myConnection()->prepare
        ("UPDATE ely_rendezvous SET rdv_statut = 'INACTIF' WHERE rdv_id = :rdv_id");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}


function traduireJour($j)
{
    switch($j){
        default :
            $jour = -1;
        break;

        case 'Monday':
            $jour = 'Lundi';
        break;

        case 'Tuesday' :
            $jour = 'Mardi';
        break;

        case 'Wednesday' :
            $jour = 'Mercredi';
        break;

        case 'Thursday' :
            $jour = 'Jeudi';
        break;

        case 'Friday' :
            $jour = 'Vendredi';
        break;

        case 'Saturday' :
            $jour = 'Samedi';
        break;

        case 'Sunday' :
            $jour = 'Dimanche';
        break;
    }
    return $jour;
}

function traductionMois($m)
{
    switch($m){
        default :
            $mois = -1;
        break;

        case 'January':
            $mois = 'Janvier';
        break;
        
        case 'February':
            $mois = 'Février';
        break;
        
        case 'March':
            $mois = 'Mars';
        break;
        
        case 'April':
            $mois = 'Avril';
        break;
        
        case 'May':
            $mois = 'Mai';
        break;
        
        case 'June':
            $mois = 'Juin';
        break;
        
        case 'July':
            $mois = 'Juillet';
        break;
        
        case 'August':
            $mois = 'Août';
        break;
        
        case 'September':
            $mois = 'Septembre';
        break;
        
        case 'October':
            $mois = 'Octobre';
        break;
        
        case 'November':
            $mois = 'Novembre';
        break;
        
        case 'December':
            $mois = 'Décembre';
        break;
    }
    return $mois;
}


function getXClientPhone($phone)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_client WHERE cli_tel = :tel");
        $request->bindParam(':tel', $phone, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    if($request->rowCount()== 1)
    {
        return $request->fetch(PDO::FETCH_ASSOC);
    }
    elseif($request->rowCount()>1)
    {
        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
        return null;
    }
}

function getXClientMail($mail)
{
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_client WHERE cli_email = :mail");
        $request->bindParam(':mail', $mail, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }

        return $request->fetch(PDO::FETCH_ASSOC);

}

function getTranchesHoraires($coi_id, $jourSemaine){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_trancheshoraires WHERE coi_id = :coi_id AND tra_jour = :jourSemaine");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->bindParam(':jourSemaine', $jourSemaine, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getAllTranchesHoraires($coi_id){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_trancheshoraires WHERE coi_id = :coi_id");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getTranchesHorairesRowCount($coi_id, $jourSemaine){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_trancheshoraires WHERE coi_id = :coi_id AND tra_jour = :jourSemaine");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->bindParam(':jourSemaine', $jourSemaine, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
}

function diff_time($t1 , $t2){
    //Heures au format (hh:mm:ss) la plus grande puis le plus petite 
     
      $tab=explode(":", $t1); 
      $tab2=explode(":", $t2); 
      
      $h=$tab[0]; 
      $m=$tab[1]; 
      $s=$tab[2]; 
      $h2=$tab2[0]; 
      $m2=$tab2[1]; 
      $s2=$tab2[2];  
     
      if ($h2>$h) { 
      $h=$h+24; 
      }  
      if ($m2>$m) { 
      $m=$m+60; 
      $h2++; 
      } 
      if ($s2>$s) { 
      $s=$s+60; 
      $m2++; 
      } 
      
      $ht=$h-$h2; 
      $mt=$m-$m2; 
      $st=$s-$s2; 
      if (strlen($ht)==1) { 
      $ht="0".$ht; 
      }  
      if (strlen($mt)==1) { 
      $mt="0".$mt; 
      }  
      if (strlen($st)==1) { 
      $st="0".$st; 
      }  
      return $ht.":".$mt.":".$st;  
     
   }
   
   function getHour($temps){
    //2020-10-26 -> array(2020,10,26)
     
      $tab=explode("-", $temps); 
      
      $year=$tab[0]; 
      $monts=$tab[1]; 
      $day=$tab[2]; 
      
      return array($year,$monts,$day);
     
   }


   

   function timeHourMinute($time){
     
      $tab=explode(":", $time); 

      
      $h=$tab[0]; 
      $m=$tab[1]; 

      return $h.":".$m;
   }

   function timeToTime($time){
     //12h00 -> 12:00:00
      $tab=explode("h", $time); 

      
      $h=$tab[0]; 
      $m=$tab[1]; 

      return $h.":".$m . ":00";
   }

   function getHourTime($time){
     
      $tab=explode(":", $time); 
      
      $h=$tab[0]; 
      $m=$tab[1]; 
      $s=$tab[2]; 

      return $h + $m/60; 
   }

   function getRdvCoiffeurAgenda($date, $coi_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_periode INNER JOIN rdv_periode ON ely_periode.per_id = rdv_periode.per_id 
        INNER JOIN ely_rendezvous ON rdv_periode.rdv_id = ely_rendezvous.rdv_id 
        INNER JOIN ely_client ON ely_client.cli_id = ely_rendezvous.cli_id 
        WHERE ely_rendezvous.coi_id = :coi_id AND ely_periode.per_date = :date");
        $request->bindParam(':date', $date);
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getRdvCoiffeurAgendaRowCount($date, $coi_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_periode INNER JOIN rdv_periode ON ely_periode.per_id = rdv_periode.per_id 
        INNER JOIN ely_rendezvous ON rdv_periode.rdv_id = ely_rendezvous.rdv_id 
        INNER JOIN ely_client ON ely_client.cli_id = ely_rendezvous.cli_id 
        WHERE ely_rendezvous.coi_id = :coi_id AND ely_periode.per_date = :date");
        $request->bindParam(':date', $date);
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
}


function transformeDateEUUS($date){
    $tab=explode("/", $date); 
      
    $jour=$tab[0]; 
    $mois=$tab[1]; 
    $annee=$tab[2]; 

    return $annee . '-' . $mois . '-' . $jour;
}

function transformeDateUSEU($date){
    $tab=explode("-", $date); 
      
    $jour=$tab[2]; 
    $mois=$tab[1]; 
    $annee=$tab[0]; 

    return $jour . '/' . $mois . '/' . $annee;
}


function insertionPeriode($datep,$hdbr,$hfbr)
{
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_periode (per_date, per_heure_min_debut, per_heure_min_fin) VALUES (:datep, :hdbr, :hfbr)");
        $request->bindParam(':datep', $datep);
        $request->bindParam(':hdbr', $hdbr);
        $request->bindParam(':hfbr', $hfbr);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function insertionPausePeriode($perid,$coiid,$brdescrip)
{
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_pause_periode (per_id, coi_id, pau_description) VALUES (:perid,:coiid, :brdescrip)");
        $request->bindParam(':perid', $perid, PDO::PARAM_INT);
        $request->bindParam(':coiid', $coiid, PDO::PARAM_INT);
        $request->bindParam(':brdescrip', $brdescrip, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getLastPeriod()
{
    try{
        $request = myConnection()->prepare
        ("SELECT max(PER_ID) as maximum FROM ely_periode");
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getAPeriode($heuredebut,$heurefin,$date,$id_coi)
{
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_periode 
        INNER JOIN rdv_periode ON ely_periode.per_id = rdv_periode.per_id
        INNER JOIN ely_rendezvous ON rdv_periode.rdv_id = ely_rendezvous.rdv_id
        where ely_periode.per_date = :date and ely_periode.per_heure_min_debut > :heuredebut and ely_periode.per_heure_min_fin < :heurefin and ely_rendezvous.coi_id = :id_coi");

        $request->bindParam(':heuredebut', $heuredebut);
        $request->bindParam(':heurefin', $heurefin);
        $request->bindParam(':date', $date);
        $request->bindParam(':id_coi', $id_coi, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}


function getPauseAgendaCoiffeur($daterdv, $coi_id){
    try{
        $request = myConnection()->prepare
        ("SELECT DISTINCT * FROM ely_periode 
        INNER JOIN ely_pause_periode ON ely_periode.per_id = ely_pause_periode.per_id 
        WHERE ely_pause_periode.coi_id = :coi_id AND ely_periode.per_date = :daterdv
        ");
        $request->bindParam(':daterdv', $daterdv);
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function getPauseAgendaCoiffeurRowCount($daterdv, $coi_id){
    try{
        $request = myConnection()->prepare
        ("SELECT DISTINCT * FROM ely_periode 
        INNER JOIN ely_pause_periode ON ely_periode.per_id = ely_pause_periode.per_id 
        WHERE ely_pause_periode.coi_id = :coi_id AND ely_periode.per_date = :daterdv
        ");
        $request->bindParam(':daterdv', $daterdv);
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
}

function heureEnMinute($temps){
    //Heures au format (hh:mm:ss) la plus grande puis le plus petite 
     
      $tab=explode(":", $temps); 
      
      $h=$tab[0]; 
      $m=$tab[1]; 


     
      return $h*60 + $m;  
     
   }

function getMinuteAvecUneHeure($temps){
//08:02:00 --> 2    
    $tab=explode(":", $temps); 
    
    $h=$tab[0]; 
    $m=$tab[1]; 
    $s=$tab[2];
    


    
    return $m; 
    
}

function heureToMinute($temps){
//Heures au format (08h00 to 460) la plus grande puis le plus petite 
    
    $tab=explode("h", $temps); 
    
    $h=$tab[0]; 
    $m=$tab[1]; 


    
    return $h*60 + $m;  
    
}

function heureToMinute2($temps){
    //Heures au format (08h00 to 460) la plus grande puis le plus petite 
        
    $tab=explode(":", $temps); 
    
    $h=$tab[0]; 
    $m=$tab[1]; 


    
    return $h*60 + $m;  
        
}

function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

function obtenirDesMinutesEnHeureMinute($time) {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return $minutes;
}


Function getAllCliRdvWhereTel($telephone){

    try{
        $request = myConnection()->prepare
        ("SELECT cli_nom,cli_prenom,coi_id,rdv_description,rdv_prix,ely_rendezvous.rdv_id,per_date,per_heure_min_debut,ser_type,ser_nom,ser_description,ser_temps_estimation FROM ely_rendezvous 
        INNER JOIN rdv_periode ON ely_rendezvous.rdv_id = rdv_periode.rdv_id 
        INNER JOIN ely_periode ON rdv_periode.per_id = ely_periode.per_id 
        INNER JOIN rdv_service ON rdv_service.rdv_id = rdv_periode.rdv_id 
        INNER JOIN ely_client ON ely_rendezvous.cli_id = ely_client.cli_id 
        INNER JOIN ely_service ON ely_service.ser_id = rdv_service.ser_id
        WHERE ely_client.cli_tel = :telephone AND ely_client.cli_statut = 'ACTIF'  ORDER BY per_date DESC");
        $request->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

Function getAllRendezVousClient($telephone){

    try{
        $request = myConnection()->prepare
        ("SELECT cli_nom,cli_prenom,coi_id,rdv_description,rdv_prix,ely_rendezvous.rdv_id,per_date,per_heure_min_debut, per_heure_min_fin 
        FROM ely_rendezvous INNER JOIN rdv_periode ON ely_rendezvous.rdv_id = rdv_periode.rdv_id 
        INNER JOIN ely_periode ON rdv_periode.per_id = ely_periode.per_id 
        INNER JOIN ely_client ON ely_rendezvous.cli_id = ely_client.cli_id 
        WHERE ely_client.cli_tel = :telephone AND ely_client.cli_statut = 'ACTIF' 
        ORDER BY per_date DESC
        ");
        $request->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}


Function getInformationServiceDUnRdv($rdv_id){

    try{
        $request = myConnection()->prepare
        ("SELECT * FROM rdv_service INNER JOIN ely_service ON rdv_service.ser_id = ely_service.ser_id WHERE rdv_service.rdv_id = :rdv_id
        ");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll(PDO::FETCH_ASSOC);
}


Function getAllCliRdvWhereRdvid($id){

    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_rendezvous 
        INNER JOIN ely_coiffeur ON ely_coiffeur.coi_id = ely_rendezvous.coi_id 
        INNER JOIN rdv_periode ON ely_rendezvous.rdv_id = rdv_periode.rdv_id 
        INNER JOIN ely_periode ON rdv_periode.per_id = ely_periode.per_id 
        INNER JOIN rdv_service ON rdv_service.rdv_id = rdv_periode.rdv_id 
        INNER JOIN ely_client ON ely_rendezvous.cli_id = ely_client.cli_id 
        INNER JOIN ely_service ON ely_service.ser_id = rdv_service.ser_id
        WHERE ely_rendezvous.rdv_id = :id ORDER BY per_date DESC");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

Function getAllCliInfoWhereRdvid($Rdvid){

    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client
        INNER JOIN ely_rendezvous ON ely_rendezvous.cli_id = ely_client.cli_id 
        INNER JOIN rdv_periode ON ely_rendezvous.rdv_id = rdv_periode.rdv_id 
        INNER JOIN ely_periode ON rdv_periode.per_id = ely_periode.per_id 
        WHERE ely_rendezvous.rdv_id = :Rdvid AND ely_client.cli_statut = 'ACTIF' ");
        $request->bindParam(':Rdvid', $Rdvid, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch();
}

function getGetClientTelEmailRowCount($cli_tel, $cli_email){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client WHERE cli_tel = :cli_tel OR cli_email = :cli_email");
        $request->bindParam(':cli_tel', $cli_tel, PDO::PARAM_STR);
        $request->bindParam(':cli_email', $cli_email, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowcount();
}

function deleteRdvClientelyrdv($rdvid){
    try{
        $request = myConnection()->prepare
        ("DELETE FROM ely_rendezvous WHERE rdv_id= :rdvid ");
        $request->bindParam(':rdvid', $rdvid, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}


function deleteRdvClientelyrdvperiode($rdvid){
    try{
        $request = myConnection()->prepare
        ("DELETE FROM rdv_periode WHERE rdv_id= :rdvid ");
        $request->bindParam(':rdvid', $rdvid, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getGetClientTelEmail($cli_tel, $cli_email){
    
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client WHERE cli_tel = :cli_tel OR cli_email = :cli_email");
        $request->bindParam(':cli_tel', $cli_tel, PDO::PARAM_STR);
        $request->bindParam(':cli_email', $cli_email, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getGetClientTel($cli_tel){
    
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client WHERE cli_tel = :cli_tel");
        $request->bindParam(':cli_tel', $cli_tel, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getGetClientEmail($cli_email){
    
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client WHERE cli_email = :cli_email");
        $request->bindParam(':cli_email', $cli_email, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch(PDO::FETCH_ASSOC);
}

function getGetClientTelRowCount($cli_tel){
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_client WHERE cli_tel = :cli_tel");
        $request->bindParam(':cli_tel', $cli_tel, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
}


function deleteRdvClientelyrdvservice($rdvid){
    try{
        $request = myConnection()->prepare
        ("DELETE FROM rdv_service WHERE rdv_id= :rdvid ");
        $request->bindParam(':rdvid', $rdvid, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getOnerdv_periode($rdvid)
{
    try{
        $request = myConnection()->prepare
        ("SELECT per_id FROM rdv_periode WHERE rdv_id= :rdvid ");
        $request->bindParam(':rdvid', $rdvid, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch();
}

function deleteRdvClientelyperiode($perid){
    try{
        $request = myConnection()->prepare
        ("DELETE FROM ely_periode WHERE per_id= :perid ");
        $request->bindParam(':perid', $perid, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function insertRendezvous($coi_id, $cli_id, $rdv_prix){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO ely_rendezvous (coi_id, cli_id, rdv_prix)
        VALUES(:coi_id,:cli_id, :rdv_prix)");
        $request->bindParam(':coi_id', $coi_id, PDO::PARAM_INT);
        $request->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
        $request->bindParam(':rdv_prix', $rdv_prix, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}


function insertRdvPeriode($rdv_id, $per_id){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO rdv_periode (rdv_id, per_id)
        VALUES(:rdv_id,:per_id)");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->bindParam(':per_id', $per_id, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function insertRdvService($ser_id, $rdv_id){
    try{
        $request = myConnection()->prepare
        ("INSERT INTO rdv_service (ser_id, rdv_id)
        VALUES(:ser_id,:rdv_id)");
        $request->bindParam(':ser_id', $ser_id, PDO::PARAM_INT);
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function selectRdvServiceWhereRdvID($rdv_id){
    try{
        $request = myConnection()->prepare
        ("SELECT * INTO rdv_service WHERE rdv_id = :rdv_id)");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    }catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request;
}

function getAPauseFromACoiffeur($coiid, $perid)
{
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_pause_periode
        INNER JOIN ely_periode ON ely_pause_periode.per_id = ely_periode.per_id
        INNER JOIN ely_coiffeur ON ely_pause_periode.coi_id = ely_coiffeur.coi_id 
        WHERE ely_pause_periode.coi_id = :coiid AND ely_pause_periode.per_id = :perid");
        $request->bindParam(':coiid', $coiid, PDO::PARAM_INT);
        $request->bindParam(':perid', $perid, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch();
}

function getInfomationPause($perid)
{
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM ely_pause_periode
        INNER JOIN ely_periode ON ely_pause_periode.per_id = ely_periode.per_id
        INNER JOIN ely_coiffeur ON ely_pause_periode.coi_id = ely_coiffeur.coi_id 
        WHERE ely_pause_periode.per_id = :perid");
        $request->bindParam(':perid', $perid, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetch();
}

function deletePauseFromACoiffeur($coiid, $perid)
{
    try{
        $request = myConnection()->prepare
        ("DELETE FROM ely_pause_periode WHERE coi_id= :coiid and per_id =:perid");
        $request->bindParam(':coiid', $coiid, PDO::PARAM_INT);
        $request->bindParam(':perid', $perid, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
}

function getAllRdvService($rdv_id)
{
    try{
        $request = myConnection()->prepare
        ("SELECT * FROM rdv_service INNER JOIN ely_service ON rdv_service.ser_id = ely_service.ser_id WHERE rdv_id = :rdv_id");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->fetchAll();
}

function rdvExiste($rdv_id){
    try {
        $request = myConnection()->prepare("SELECT * FROM ely_rendezvous where rdv_id = :rdv_id and rdv_statut = 'ACTIF'");
        $request->bindParam(':rdv_id', $rdv_id, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        header("Location:error?message=".$e->getMessage());
    }
    return $request->rowCount();
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}