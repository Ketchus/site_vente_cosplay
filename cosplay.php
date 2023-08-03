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

if (!$idcur)
{
    header('Location:http://localhost/exemple/login.php');
    exit();
}

if (isset($_POST["ajouter_au_panier"])) {
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

$resultat = mysqli_query($conn, "SELECT * FROM article WHERE type_article = 'cosplay'");
while ($ligne = mysqli_fetch_assoc($resultat)) 
{
    echo "<div class='article'>";

    $image = $ligne["image"];
    $Nom = $ligne["NOM"];
    $ref = $ligne["ref"];
    $Prix = $ligne["Prix"];  
    $new_prix= ($ligne["Prix"] - ($ligne["Prix"] * $ligne["Pourcentage_Solde"])/100);
    $solde = $ligne["Pourcentage_Solde"];
    $boolsolde=$ligne["solde"];

    if( $boolsolde == 'oui') {

    

    echo "<a href='articledesc.php?id=" . $ligne["ID_ARTICLE"] . "'>";
    echo "<img src='image/$image' width='300' height='300' />";
    echo "</a><br/>";


    echo "<div class='ref'>" . $ref . "</div>";
    echo "<div class='nom'>" . $Nom . "</div>";
    echo "<div class='prixsolde'>" . $Prix ."$</div>";
    echo "<div class='newprix'>" . $new_prix . "$</div>";
 
    echo "<form method='post' action='cosplay.php'>";
    echo "<input class='names' type='number' name='quantite' id='quantite' min='1' value='1' required>";
    echo "<input type='hidden' name='id_article' value='" . $ligne["ID_ARTICLE"] . "'>";


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
    } else{

    echo "<a href='articledesc.php?id=" . $ligne["ID_ARTICLE"] . "'>";
    echo "<img src='image/$image' width='300' height='300' />";
    echo "</a><br/>";

    echo "<div class='ref'>" . $ref . "</div>";
    echo "<div class='nom'>" . $Nom . "</div><br/>";
    echo "<div class='prix'>" . $Prix ."$</div>";
 
    echo "<form method='post' action='cosplay.php'>";
    echo "<input class='names' type='number' name='quantite' id='quantite' min='1' value='1' required>";
    echo "<input type='hidden' name='id_article' value='" . $ligne["ID_ARTICLE"] . "'>";

    echo "<select class='names' name='Taille'>";
    echo "<option>XS</option>";
    echo "<option>S</option>";
    echo "<option>M</option>";
    echo "<option>L</option>";
    echo "<option>XL</option>";
    echo "<option>XXL</option>";
    echo "</select><br/><br/>";

    echo "<input type='submit' name='ajouter_au_panier' value='Ajouter au panier'><br/>";
    echo "</form>";
    }

    echo "</div>";
}  

mysqli_close($conn);





?>

        

    </body>
    
</html>