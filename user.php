<?php
session_start();
require_once("db.php");
require_once("header.php");

if(isset($_POST['logout'])) {
    header("Location: logout.php");
    exit();
}

?>



<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="login.css">
        <title>ORK_COSPLAY LOGIN</title>

    </head>

    <body>

        <?php

            $idusermoi = $_SESSION["user_id"];
            if (is_numeric($idusermoi)) {
                // Récupérer les données de la table
              $sql = "SELECT * FROM client WHERE ID_CLIENT = " . $idusermoi;
              $resultat = mysqli_query($conn, $sql);
            
              if ($resultat && mysqli_num_rows($resultat) > 0) {
                $row = mysqli_fetch_array($resultat);
                $nom = $row['nom'];
                $prenom = $row['prenom'];
                $username = $row['username'];
                $mail = $row['Mail'];
                $adresse = $row['Adresse'];  
                $telephone = $row['Telephone'];
                $password = $row['password'];
              } else {
                header('Location: http://localhost/exemple/login.php');
                exit();
              }
            } else {
              header('Location: http://localhost/exemple/login.php');
              exit();
            }

            if(isset($_POST['Modifmdp'])){
                $password = $_POST['password2'];
            
                // Vérifier si le client existe dans la table client
                $sql = "SELECT * FROM client WHERE ID_CLIENT = " . $idusermoi;
                $result = mysqli_query($conn, $sql);
            
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result);
                    $current_password = $row['password'];
                    $entered_password = $_POST['password'];
            
                    if($current_password == $entered_password){
                        // Modifier le mot de passe dans la table client
                        $sql = "UPDATE client SET password = '$password' WHERE ID_CLIENT = '$idusermoi'";
            
                        if(mysqli_query($conn, $sql)){
                            // Rediriger l'utilisateur vers la page de connexion après la réinitialisation réussie
                            header('Location: /exemple/user.php');
                            exit;
                        } else {
                            echo "Erreur: " . $sql . "<br>" . mysqli_error($conn);
                        }
                    } else {
                        echo '<div class="TITRE"><h1>Mot de passe incorrect</H1></div>';
                    }
                } else {
                    echo "Client non trouvé";
                }
            }
            

           
        ?>
        

        <div class ="formulaire">
            <h1 class= "ok" style="text-shadow: 5px 5px rgb(255, 255, 255)">Mon Compte</h1>
            <form action="method-get.php" method="get">
            
                
                <input class="form-input" type="text" name="username" placeholder="Username" value="<?php echo  $username ; ?>" disabled /><br>
                <input class="form-input" type="text" name="nom" placeholder="Nom" value="<?php echo  $nom ; ?>" disabled /><br>
                <input class="form-input" type="text" name="prenom"  placeholder="Prénom" value="<?php echo  $prenom ; ?>" disabled /><br>
                <input class="form-input" type="text" name="Adresse"  placeholder="Adresse" value="<?php echo  $adresse ; ?>" disabled /><br>
                <input class="form-input" type="text" name="Mail"  placeholder="Mail" value="<?php echo  $mail ; ?>" disabled /><br>
                <input class="form-input" type="text" name="Telephone" placeholder="Téléphone" value="<?php echo  $telephone; ?>" disabled /><br>

            </form>
            <form action="user.php" method="post">
                <input class="form-input" type="password" placeholder="ancien mot de passe"  name="password" ><br>

                <input class="form-input" type="password" placeholder="nouveau mot de passe" name="password2" ><br>

                <input class="bouton" type="submit" name = "Modifmdp"  value="Modifier votre mot de passe">
            </form>

            <form action="logout.php" method="post">
                <button class="bouton" type="submit" name="logout">Se déconnecter</button>
            </form>


        </div>


          

    </body>
    
</html>