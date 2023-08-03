<?php
session_start();

require_once("db.php");
require_once("header.php");
require_once("bas.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="panier.css">
        <title>ORK_COSPLAY LOGIN</title>

    </head>

    <body>


        
    <?php

        $idusermoi = $_SESSION["user_id"];

        // Récupérer la quantité totale de tous les articles dans le panier
        $query2 = "SELECT SUM(Quantite) AS quantite_totale FROM panier WHERE ID_CLIENT = $idusermoi;";
        $total_quantite = mysqli_query($conn, $query2);

        // Vérifier si la requête a réussi et afficher la quantité totale
        if ($total_quantite) {
            $total_quantite_row = mysqli_fetch_assoc($total_quantite);
            
            if ($total_quantite_row["quantite_totale"] == 0) {
                echo "<div class='titre' style='position: relative; display: flex; justify-content: center; font: 60px sans-serif; color:rgb(243, 205, 248); 
                margin-top: 20% ;text-shadow: 2px 2px rgb(255, 255, 255);'>Votre panier est vide.</div>";            
                } else {
               // Récupérer les informations sur l'article depuis la table article
                $query = "SELECT SUM(panier.Quantite) AS quantite_totale, panier.ID_ARTICLE AS id_article, article.image AS imgarticle, article.Pourcentage_Solde AS PourcSoldArt, 
                article.NOM AS Nomarticle, article.Prix AS Prix, panier.Taille AS Taille 
                FROM panier 
                JOIN article ON panier.ID_ARTICLE = article.ID_ARTICLE 
                WHERE panier.ID_CLIENT = $idusermoi 
                GROUP BY panier.ID_ARTICLE, panier.Taille ;";
 

                // Exécuter la requête SQL et vérifier si elle a réussi
                $resultat = mysqli_query($conn, $query);

                if ($resultat) {
                    // Boucle pour afficher les données
                    while ($ligne = mysqli_fetch_assoc($resultat)) {
                        // afficher les données ici
                        echo"<div class='article'>";

                        $image = $ligne["imgarticle"];
                        $Nom = $ligne["Nomarticle"];
                        $Taille = $ligne["Taille"];
                        $quantite = $ligne["quantite_totale"];
                        $Prix = ($ligne["Prix"] - ($ligne["Prix"] * $ligne["PourcSoldArt"])/100);  
                        $total = $quantite * $Prix ; // calcul du prix total

        
                        echo "<img src ='image/$image' class ='dac' width='250' height='250' />" . "<br/>"; 
                        echo "<div class='nom'>" . $Nom . "</div>";
                        echo "<div class='taille'> Taille:"  . $Taille . "</div>";
                        echo "<div class='prix'> Prix à l'unité :" . $Prix . "</div>";
                        echo "<div class='nom'> Quantité : " . $quantite . "</div>";
                        echo "<div class='prix'> Prix total : " . $total. "$</div>";
                        echo "<div class='supp'>";
                        
                        

                        echo "<form method='post' action='ajoute_quantite.php'>";
                            echo "<input class='names' type='number' name='quantite' id='quantite' min='1' value='$quantite'>";
                            echo "<input type='hidden' name='article_id' value='" . $ligne["id_article"] . "'>";
                            echo "<input type='hidden' name='taille' value='$Taille'>";
                            echo "<input type='submit' value='Mettre à jour'>";
                        echo "</form>";

                        echo "<form method='post' action='supp.php'>";
                            echo "<input type='hidden' name='id_article' value='" . $ligne["id_article"] . "'>";
                            echo "<input class = 'supprimer' type='submit' name='supprimer' value='suprimer'><br>";
                        echo "</form>";
                        
                        echo "</div>";

                        echo" </div> ";
                    }
                

                    echo"<div >";
                    echo "<form method='post' action='payer.php'>";
                    echo "<input class='passer' type='submit' name='Passer la commande' value='Passer la commande'>";
                    echo "</form>";
                    echo" </div> ";

                } else {
                    echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
                }
            }
        }

    ?>


    </body>
    
</html>