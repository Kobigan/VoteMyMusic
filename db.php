<?php
$servername = "localhost";
$username = "root"; // Laissez ceci vide
$password = ""; // Laissez ceci vide
$dbname = "prjt_music";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
?>