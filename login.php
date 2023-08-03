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
        <link rel="stylesheet" href="login.css">
        <title>ORK_COSPLAY LOGIN</title>

    </head>

    <body>

        <div class="formulaire">
            <h1 style="text-shadow: 5px 5px rgb(255, 255, 255)">LOGIN</h1>
            <form method="post" action="login.php">

                <span class="input-item">
                    <i class="fa fa-user-circle"></i>
                </span>
                <input class="form-input" type="text" placeholder="UserName" id="username" name="Username" required><br>

                <span class="input-item">
                    <i class="fa fa-key"></i>
                </span>
                <input class="form-input" type="password" placeholder="Password" id="passeword"  name="Password" required><br>

                <input class="bouton" type="submit" value="Se connecter">
            
                <a href="oublimdp.php" title="Cosplay">
                    <h3 style="text-align: center ">Mot de passe oublié ?</h3>
                </a>
                <div class="inscrit">
                    <a href="inscription.php" title="Cosplay">
                        <h2 style="text-align: center ">S'inscrire</h2>
                    </a>
                </div>
            </form>
        </div>
        
        <?php
            // Vérifiez si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Obtenez le nom d'utilisateur et le mot de passe du formulaire
                $username = $_POST["Username"];
                $password = $_POST["Password"];
      
                // Préparez une instruction SQL pour sélectionner l'utilisateur dans la base de données
                $sql = "SELECT ID_CLIENT, username, password FROM client WHERE username = '" . $username . "'";
                $result = mysqli_query($conn, $sql);
                $ligne = mysqli_fetch_array($result);


                // Vérifiez si un utilisateur a été trouvé avec le nom d'utilisateur donné
                if ($ligne != null) {
                    $user_id = $ligne["ID_CLIENT"];
                    $user_username = $ligne["username"];
                    $user_password = $ligne["password"];

                   
                    // Vérifiez si le mot de passe correspond au hachage du mot de passe dans la base de données
                    if ($password ==  $user_password) {
                        // Le mot de passe est correct, démarrez une session pour l'utilisateur

                        $_SESSION["user_id"] = $user_id;
                        header("Location: index.php");
                    } else {
                        // Le mot de passe est incorrect
                        $error = "Mot de passe incorrect";
                    }
                } else {
                    // Aucun utilisateur trouvé avec le nom d'utilisateur donné
                    $error = "Utilisateur introuvable";
                }
            }

        ?>   

    </body>
    
</html>