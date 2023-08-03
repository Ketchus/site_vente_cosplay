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

if (isset($_GET["recherche"])) {
    $nomart = $_GET["recherche"];            

    $query = "SELECT * FROM article WHERE NOM LIKE '%$nomart%'";
    $result = mysqli_query($conn, $query);

    while ($ligne = mysqli_fetch_assoc($result)) 
    {
        echo "<div class='article'>";

        $image = $ligne["image"];
        $Nom = $ligne["NOM"];
        $ref = $ligne["ref"];
        $Prix = $ligne["Prix"];  
    
        echo "<img src ='image/$image' width='300' height='300' />" . "<br/>"; 
        echo "<div class='ref'>" . $ref . "</div>";
        echo "<div class='nom'>" . $Nom . "</div>";
        echo "<div class='prix'>" . $Prix . "</div>";

        echo "</div>";
    }  

    mysqli_close($conn);
} 

?>
