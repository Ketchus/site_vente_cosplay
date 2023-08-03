<?php
session_start();
require_once("db.php");
require_once("header.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$idusermoi = $_SESSION["user_id"];

$sql = "SELECT * FROM client WHERE ID_CLIENT = $idusermoi";
$resultat = mysqli_query($conn, $sql);

if ($resultat && mysqli_num_rows($resultat) > 0) {
    $row = mysqli_fetch_array($resultat);
    $nom = $row['nom'];
    $prenom = $row['prenom'];
    $mail = $row['Mail'];
    $adresse = $row['Adresse'];  
    $telephone = $row['Telephone'];
}

if (isset($_POST["payer"])) {
    // Récupérer le mode de paiement sélectionné
    $paiement = $_POST["mode_paiement"];

    // Récupérer les informations du panier du client
    $sqli = "SELECT panier.ID_PANIER AS id_panier, panier.ID_ARTICLE AS id_article, article.Prix AS Prix, panier.Quantite AS Quantite
             FROM panier 
             JOIN article ON panier.ID_ARTICLE = article.ID_ARTICLE 
             WHERE panier.ID_CLIENT = $idusermoi 
             GROUP BY panier.ID_ARTICLE;";
    $resultatli = mysqli_query($conn, $sqli);

    $total = 0;
    $articles = [];

    if ($resultatli) {
        while ($ligne = mysqli_fetch_assoc($resultatli)) {
            $quantite = $ligne["Quantite"];
            $prix = $ligne["Prix"];
            $total_article = $quantite * $prix;
            $total += $total_article;
            $articles[] = [
                'id_panier' => $ligne['id_panier'],
                'id_article' => $ligne['id_article'],
                'quantite' => $quantite,
                'prix' => $prix,
                'total_article' => $total_article,
            ];
        }

        // Ajouter les informations de l'article à la requête d'insertion
        $adresse_livraison = "$nom $prenom $adresse $mail $telephone";

        foreach ($articles as &$article) {
            $article['total_article'] = $article['quantite'] * $article['prix'];

            $sqlii = "INSERT INTO commande (ID_PANIER, ID_CLIENT, adresse_livraison, prix_total, paiement) 
                      VALUES ('$article[id_panier]', '$idusermoi', '$adresse_livraison', '$article[total_article]', '$paiement' )";
            if (mysqli_query($conn, $sqlii)) {
                // supprimer l'article du panier
                $id_panier = $article['id_panier'];
                unset($_SESSION['panier'][$id_panier]);
            } else {
                echo "Erreur: " . $sqlii . "<br>" . mysqli_error($conn);
            }
        }

        // Mettre à jour le champ "prix_total" de la dernière commande
        $last_insert_id = mysqli_insert_id($conn);
        $sqliii = "UPDATE commande SET prix_total = '$total' WHERE ID_COMMANDE = $last_insert_id";
        if (mysqli_query($conn, $sqliii)) {
            // Succès
        } else {
                echo "Erreur: " . $sqliii . "<br>" . mysqli_error($conn);
            }
    
            // Rediriger l'utilisateur vers la page du panier
            header('Location: http://localhost/exemple/panier2.php');
            // Terminer l'exécution du script pour empêcher toute autre sortie
            exit;
        }
    }
    
    if (isset($_POST["annuler"])) {
        // Rediriger l'utilisateur vers la page du panier
        header('Location: http://localhost/exemple/panier2.php');
        // Terminer l'exécution du script pour empêcher toute autre sortie
        exit;
    }

?>


<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="payer.css">
        <title>ORK_COSPLAY</title>

    </head>

    <body>

        <h1 class = "titre" style="text-shadow: 3px 3px rgb(255, 255, 255)">COMMANDER</h1>

        <div  class ="formulaire">
        <h1 class = "titre1" style="text-shadow: 3px 3px rgb(255, 255, 255)">Adresse de livraison</h1>
        <form action="method-get.php" method="get">
            <input class="form-input" type="text" name="nom" placeholder="Nom" value="<?php echo  $nom; ?>"  /><br>
            <input class="form-input" type="text" name="prenom" placeholder="Prénom" value="<?php echo  $prenom; ?>"  /><br>
            <input class="form-input" type="text" name="Adresse" placeholder="Adresse" value="<?php echo  $adresse; ?>"  /><br>
            <input class="form-input" type="text" name="Mail" placeholder="Mail" value="<?php echo  $mail; ?>"  /><br>
            <input class="form-input" type="text" name="Telephone" placeholder="Téléphone" value="<?php echo  $telephone; ?>" /><br>
        </form>
        

        
        <h1 class = "titre2" style="text-shadow: 2px 2px rgb(255, 255, 255)" >Mode de paiement</h1>
        
        <form method="POST" action="payer.php">  
            <select class="paiement" name="mode_paiement">
                <option value="Paypal">Paypal</option>
                <option value="Carte bleue">Carte bleue</option>
            </select>
            <div class='passer'>
            <input type="submit" name="payer" value="Payer"><br>
            </div>
        </form>

        <form method="POST" action="payer.php">
            <div class='passer'>
                <input type="submit" name="annuler" value="Annuler">
            </div>
        </form>

    </body>
    
</html>