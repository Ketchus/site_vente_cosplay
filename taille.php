<?php
session_start();
require_once("db.php");

?>

<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styleindex.css">
        <title>ORK_COSPLAY</title>

    </head>

    <body>

        <?php
            print_r($_POST);

            $idart = $_POST["id_article"];            
            $qte = $_POST["quantite"];            
            $idusermoi = $_SESSION["user_id"];

            $sql = "INSERT INTO panier VALUES (null, " . $idusermoi . "," . $idart . "," . $qte . ")";

            mysqli_query($conn, $sql);

            // Message de confirmation pour l'utilisateur
             echo "<p>L'article a été ajouté au panier.</p>";

            header('Location:/exemple/panier2.php');

        ?>

        
        

    </body>
    
</html>