<?php
$host = 'localhost';     // l'adresse de MariaDB
$dbname = 'helpdesk';    // le nom de la base
$username = 'mthibaut';      // nom d'utilisateur
$password = 'btsinfo';          // mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
