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
        <?php

        if(isset($_POST['submit'])){
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $username = $_POST['username'];
            $mail = $_POST['Mail'];
            $adresse = $_POST['Adresse']; 
            $cp = $_POST['CP'];  
            $ville = $_POST['Ville']; 
            $telephone = $_POST['Telephone'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            // Vérifier si le mot de passe correspond à la confirmation du mot de passe
            if ($password !== $confirmPassword) {
                echo "Le mot de passe et la confirmation du mot de passe ne correspondent pas.";
                exit;
            }

            // Vérifier la longueur du mot de passe
            $passwordLength = strlen($password);
            if ($passwordLength < 8 || $passwordLength > 12) {
                echo "La longueur du mot de passe doit être comprise entre 8 et 12 caractères.";
                exit;
            }

            // Vérifier la longueur du Code Postal
            $cpLength = strlen($cp);
            if ($cpLength !== 5) {
                echo "La longueur du code postal doit être égale à 5 caractères.";
                exit;
            }

            // Vérifier si le mot de passe contient au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial
            if (!preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[^a-zA-Z0-9]/", $password)) {
                echo "Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.";
                exit;
            }

            // Vérifier si l'adresse e-mail existe déjà dans la base de données
            $checkQuery = "SELECT  Mail FROM client WHERE Mail='$mail'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                echo "L'adresse e-mail existe déjà dans la base de données.";
                exit;
            }

            // Insérer les données dans la table
            $sql = "INSERT INTO client (ID_CLIENT,nom, prenom, username, Mail, Adresse, Telephone, password, Ville, CP) 
            VALUES ('','$nom', '$prenom', '$username', '$mail', '$adresse', '$telephone', '$password','$cp', '$ville')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                // Exécuter une requête SELECT pour récupérer les informations de l'utilisateur nouvellement inscrit
                $sql = "SELECT * FROM client WHERE ID_CLIENT='$result'";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $user_id = $row["ID_CLIENT"];
                    // Stocker les informations de l'utilisateur dans la session
                    $_SESSION["user_id"] = $user_id;
                }

                // Rediriger l'utilisateur vers la page d'accueil après l'inscription réussie
                header('Location: /exemple/index.php');
                exit;
            } 
        
        }

        
        ?>

        <div class="formulaire">
            <h1 style="text-shadow: 5px 5px rgb(255, 255, 255)">S'inscrire</h1>
            <form action="" method="POST">
                <input class="form-input" type="text" name="username" placeholder="Username" required><br>
                <input class="form-input" type="text" name="nom" placeholder="Nom" required><br>
                <input class="form-input" type="text" name="prenom" placeholder="Prénom" required><br>
                <input class="form-input" type="text" name="Adresse" placeholder="Adresse" required><br>

                <input class="form-input" type="text" name="CP" placeholder="Code postal" required><br>
                <input class="form-input" type="text" name="Ville" placeholder="Ville" required><br>

                <input class="form-input" type="text" name="Mail" placeholder="Mail" required><br>
                <input class="form-input" type="text" name="Telephone" placeholder="Téléphone" required><br>
                <span class="input-item">
                    <i class="fa fa-key"></i>
                </span>
                <input class="form-input" type="password" placeholder="mot de passe" id="mdp" name="password" required><br>
                <input class="form-input" type="password" placeholder="confirmer le mot de passe" id="mdp" name="confirmPassword" required><br>

                <input class="bouton" type="submit" name="submit" value="S'inscrire">
            </form>
        </div>
    </body>
</html>
