<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cosplay.css">
    <title>ORK_COSPLAY</title>
</head>
<body>
<?php

session_start();
require_once("db.php");
require_once("header.php");
require_once("bas.php");

$idcur = isset($_SESSION["user_id"]);

if (!$idcur) {
    header('Location: http://localhost/exemple/login.php');
    exit();
}

// Vérifier si l'ID de l'article est présent dans la requête GET
if (isset($_GET["id"])) {
    $idart = $_GET["id"];
    
    // Requête pour récupérer les informations de l'article
    $query = "SELECT * FROM article WHERE id_article = $idart";
    $result = mysqli_query($conn, $query);
    $article = mysqli_fetch_assoc($result);
    
    if ($article) {
        // Afficher les détails de l'article
        echo "<div class='article'>";
        echo "<img src='image/" . $article["image"] . "' width='300' height='300' /><br/>";
        echo "<div class='ref'>" . $article["ref"] . "</div>";
        echo "<div class='nom'>" . $article["NOM"] . "</div>";
        echo "<div class='prix'>" . $article["Prix"] . "</div>";
        echo "<div class='desc'>" . $article["Description"] . "</div>";
        
        echo "<form method='post' action='cosplay.php'>";
        echo "<input class='names' type='number' name='quantite' id='quantite' min='1' value='1' required>";
        echo "<input type='hidden' name='id_article' value='" . $article["ID_ARTICLE"] . "'>";
    
        echo "<select class='names' name='Taille'>";
        echo "<option>XS</option>";
        echo "<option>S</option>";
        echo "<option>M</option>";
        echo "<option>L</option>";
        echo "<option>XL</option>";
        echo "<option>XXL</option>";
        echo "</select>";
    
        echo "<input type='submit' name='ajouter_au_panier' value='Ajouter au panier'><br/>";
        echo "</form>";
    
        echo "</div>";
    } else {
        echo "Article non trouvé.";
    }
} else {
    echo "ID de l'article manquant.";
}

mysqli_close($conn);
?>
</body>
</html>
