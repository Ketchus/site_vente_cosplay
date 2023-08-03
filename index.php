<?php
session_start();
require_once("db.php");
require_once("header.php");

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

        <div class ="bvimg">
            <img src="image/fille.gif" alt="BIENVENUE" width="300" height="300">
        </div>    

        <h1 class= "titre">BIENVENUE</h1>

        <div class='vente' >
            <h2 class='cat'>MEILLEURS VENTES</h2>"
            <?php
            
                // Récupérer les informations sur l'article depuis la table article
                $query = "CALL Top3('vente');";

                // Boucle pour afficher les données
                $resultat = mysqli_query($conn, $query);
          
                while ($ligne = mysqli_fetch_assoc($resultat))
                {           
                echo"<div class='article'>";

                    $image = $ligne["image"];
                    $Nom = $ligne["NOM"];
                    $ref = $ligne["ref"];
                    $Prix = $ligne["Prix"];  
    
                    echo "<a href='articledesc.php?id=" . $ligne["ID_ARTICLE"] . "'>";
                    echo "<img src='image/$image' class ='dac' />";
                    echo "</a><br/>";
                    echo "<div class='ref'>" . $ref . "</div>";
                    echo "<div class='nom'>" . $Nom . "</div>";
                    echo "<div class='prix'>" . $Prix . "$</div>";

                echo" </div> ";
                }

                if(isset($_POST['Visu'])){
                    // Rediriger l'utilisateur vers la page du panier
                    header('Location: http://localhost/exemple/article.php');
                    // Terminer l'exécution du script pour empêcher toute autre sortie
                    exit;
                }

                echo"<form action='index.php' method='post'>";
                echo"<input class='visu' type='submit' name = 'Visu'  value='Voir tous les articles'>";
                echo"</form>";

            ?>

        </div>

        <h1 class="cat">Catégories</h1>

        <ul class="bas">
            
            <li class="imm">    
                <a href="cosplay.php" title="Cosplay">
	                <img alt="cosplay" src="image/cosplay.png"  class= "ok" />
                </a>
            </li>

            <li class="imm">
                <a href="accessoires.php" title="accessoires">
	                <img alt="accessoires" src="image/accessoires.png"  class= "ok" />
                </a>
            </li>

            <li class="imm">
                <a href="katana.php" title="katana">
	                <img alt="katana" src="image/katana.png" class= "ok" />
                </a>
            </li>

        </ul>

        
    

    </body>
    
</html>