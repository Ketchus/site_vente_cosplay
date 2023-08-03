<?php
session_start();

require_once("db.php");
require_once("header.php");

if(isset($_POST['submit'])){
    $mail = $_POST['Adressemail'];
    $password = $_POST['motdepasse'];

    // Vérifier si l'adresse mail existe dans la table client
    $sql = "SELECT * FROM client WHERE Mail = '$mail'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        // Modifier le mot de passe dans la table client
        $sql = "UPDATE client SET password = '$password' WHERE Mail = '$mail'";

        if(mysqli_query($conn, $sql)){
            // Rediriger l'utilisateur vers la page de connexion après la réinitialisation réussie
            header('Location: /exemple/index.php');
            exit;
        } else {
            echo "Erreur: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Adresse mail non trouvée";
    }
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
        
        <div class="formulaire">
            <h1 style="text-shadow: 5px 5px rgb(255, 255, 255)">Réinitialiser le mot de passe</h1>
            <form action="" method="POST">
                <input class="form-input" type="text" placeholder="Adresse mail" id="username" name="Adressemail" required><br>
                <input class="form-input" type="text" placeholder="Nouveau mot de passe" id="mdr" name="motdepasse" required><br>
                <input class="bouton" type="submit" name="submit" value="Réinitialiser le mot de passe">
            </form>
        </div>

    </body>
    
</html>