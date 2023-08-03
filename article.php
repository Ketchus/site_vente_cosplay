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


if (isset($_POST["ajouter_au_panier"])) {
    if (!isset($_SESSION["user_id"])) {
        header('Location: http://localhost/exemple/article.php');
        echo "<p>Veuillez vous connecter pour ajouter des articles au panier.</p>";
        exit;
    } else {
        $idart = $_POST["id_article"];            
        $qte = $_POST["quantite"];            
        $idusermoi = $_SESSION["user_id"];
        $taille = $_POST['Taille'];

        $query = "SELECT * FROM article WHERE id_article = $idart";
        $result = mysqli_query($conn, $query);

        $ligne = mysqli_fetch_assoc($result);
        $nom_article = $ligne['NOM'];
        $prix_article = $ligne['Prix'];

        $query = "SELECT * FROM panier WHERE id_client = $idusermoi AND id_article = $idart AND taille = '$taille'";
        $result = mysqli_query($conn, $query);
        $panier_article = mysqli_fetch_assoc($result);

        if ($panier_article) {
            $quantite = $panier_article["Quantite"] + $qte;
            $query = "UPDATE panier SET Quantite = $quantite WHERE ID_PANIER = " . $panier_article["ID_PANIER"];
        } else {
            $query = "INSERT INTO panier (ID_PANIER, id_client, id_article, quantite, taille) VALUES (null, $idusermoi, $idart, $qte, '$taille')";
        }
        mysqli_query($conn, $query);
        // Rediriger l'utilisateur vers la page du panier
        header('Location: http://localhost/exemple/panier2.php');
        // Terminer l'exécution du script pour empêcher toute autre sortie
        exit;
    }
}


// Récupérer les informations sur l'article depuis la table article
$query = "SELECT * FROM article";
$resultat = mysqli_query($conn, $query);

// Récupérer tous les résultats dans un tableau
$articles = mysqli_fetch_all($resultat, MYSQLI_ASSOC);

// Boucle pour afficher les données
foreach ($articles as $article)
{
    
    echo "<div class='article'>";

    $image = $article["image"];
    $Nom = $article["NOM"];
    $ref = $article["ref"];
    $Prix = $article["Prix"];  
    $categorie = $article["type_article"];
    
    echo "<a href='articledesc.php?id=" . $article["ID_ARTICLE"] . "'>";
    echo "<img src='image/$image' width='300' height='300' />";
    echo "</a><br/>"; 
    echo "<div class='ref'>" . $ref . "</div>";
    echo "<div class='nom'>" . $Nom . "</div>";
    echo "<div class='prix'>" . $Prix . "</div>";
 
    echo "<form method='post' action='cosplay.php'>";
    echo "<input class='names' type='number' name='quantite' id='quantite' min='1' value='1' required>";
    echo "<input type='hidden' name='id_article' value='" . $article["ID_ARTICLE"] . "'>";

    if ($categorie == "cosplay") {
        echo "<select class='names' name='Taille'>";
        echo "<option>XS</option>";
        echo "<option>S</option>";
        echo "<option>M</option>";
        echo "<option>L</option>";
        echo "<option>XL</option>";
        echo "<option>XXL</option>";
        echo "</select>";
    }
    else if ($categorie == "accessoire" || $categorie == "katana") {
        echo "<select class='names' name='Taille'>";
        echo "<option>Taille unique</option>";
        echo "</select>";
    }

    echo "<input type='submit' name='ajouter_au_panier' value='Ajouter au panier'><br/>";
    echo "</form>";

    echo "</div>";

}  

mysqli_close($conn);


?>

        

    </body>
    
</html>