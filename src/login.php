<?php 
/** 
 ** Salon de coiffure Eylsée - Victor, Jarod et Maxime
 */

require 'includes/dbfunctions.php';
session_start();

    if (isset($_POST['formConnection'])) 
    {   
        $mailconnection=htmlspecialchars(trim(strtolower($_POST['mailconnection'])));
        $mdpconnection=trim(sha1($_POST['mdpconnection']));
        if (!empty($mailconnection) AND !empty($mdpconnection)) 
        {
            $Userexist=getAllCoiffeurWhereMailAndMdp($mailconnection,$mdpconnection)->rowCount();
            if ($Userexist==1) 
            {
                $userinfo=getAllCoiffeurWhereMailAndMdp($mailconnection,$mdpconnection)->fetch(PDO::FETCH_ASSOC);
                $_SESSION['COI_ID']=$userinfo['COI_ID'];
                $_SESSION['COI_NOM']=$userinfo['COI_NOM'];
                $_SESSION['COI_MAIL']=$userinfo['COI_MAIL'];
                $_SESSION['COI_POSTE']=$userinfo['COI_POSTE'];
                header("Location:index?");
            }
            else
            {
                $error="<div class=\"alert alert-danger\" role=\"alert\">
                        Connexion impossible veuillez recommencer
                    </div>";
            }
        }
        else
        {
            $error="<div class=\"alert alert-danger\" role=\"alert\">
                        Tous les champs doivent etre remplis!
                    </div>"; 
        }
    }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> 
    <title>Document</title>
</head>
<body class="login" style="background-color: #fed136;">
        <form action="login" method="POST" class="box">
            <h1>Login</h1>
            <input type="email" name="mailconnection" id=""  placeholder="Entrez mail ">
            <input type="password" name="mdpconnection" id="" placeholder="Mot de passe">
            <span><a class="text-warning" href="RecupérerMotdePasse" style="font-size:20px">Mot de passe oublié?</a></span>
            <input type="submit" name="formConnection" id="formConnection" value="Se connecter">
            <?php 
                if(isset($error))
                {
                    echo $error;
                }
            ?>
        </form>
</body>
</html>