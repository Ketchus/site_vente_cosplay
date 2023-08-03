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

if (isset($_POST['article_id']) && isset($_POST['taille']) && isset($_POST['quantite'])) {
    $articleId = $_POST['article_id'];
    $taille = $_POST['taille'];
    $nouvelleQuantite = $_POST['quantite'];

    // Effectuer les opérations nécessaires pour mettre à jour la quantité dans le panier
    // Assurez-vous de valider les données et d'effectuer les requêtes SQL appropriées

    // Exemple de requête SQL pour mettre à jour la quantité
    $query = "UPDATE panier SET Quantite = '$nouvelleQuantite' WHERE ID_ARTICLE = '$articleId' AND Taille = '$taille'";
    
    // Exécutez la requête SQL pour mettre à jour la quantité
    if (mysqli_query($conn, $query)) {
        // La mise à jour de la quantité a réussi
        // Redirigez l'utilisateur vers la page du panier ou effectuez d'autres actions si nécessaire
        header('Location:http://localhost/exemple/panier2.php');
        exit;
    } else {
        // La mise à jour de la quantité a échoué
        // Affichez un message d'erreur ou effectuez d'autres actions si nécessaire
        echo "Erreur lors de la mise à jour de la quantité : " . mysqli_error($conn);
    }
}
?>