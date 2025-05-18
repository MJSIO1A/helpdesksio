<?php
session_start();
require_once 'includes/db.php';

$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion technicien</title>
    <link rel="stylesheet" href="csstech/connec.css">
</head>
<body>
<div class="container">
    <h1>Connexion</h1>

    <?php if ($error): ?>
        <p style="color: red;">Identifiants incorrects.</p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required><br><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <button type="submit">Se connecter</button>
    </form>

    <br>
	<p>Pas encore de compte ? <a href="register.php">Créer un compte technicien</a></p>
    <a href="index.php">← Retour à l'accueil</a>
</div>
</body>
</html>
