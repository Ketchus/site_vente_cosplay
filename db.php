<?php
    // Connectez-vous à la base de données
    // informations d'identification de la base de données
    $host = 'localhost';
    $user = 'root';
    $password ='';
    $db_name = 'ork';

    // Connexion à la base de données
    $conn = mysqli_connect($host, $user, $password, $db_name);

    // Vérification de la connexion
    if (!$conn) {
        die("Échec de la connexion à la base de données: " . mysqli_connect_error());
    }
?>