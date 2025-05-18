<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$success = false;
$error = false;

// Récupération de la clé de création (s'il y en a une)
$stmt = $pdo->query("SELECT cle_creation_hash FROM config LIMIT 1");
$cle_data = $stmt->fetch();
$cle_hash_en_base = $cle_data ? $cle_data['cle_creation_hash'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $cle_fournie = $_POST['cle'] ?? '';

    if ($username && $password && $password === $confirm && $cle_fournie) {
        // Si une clé existe déjà : on vérifie qu'elle est correcte
        if ($cle_hash_en_base) {
            if (!password_verify($cle_fournie, $cle_hash_en_base)) {
                $error = "Clé d'accès invalide. Vous ne pouvez pas créer de compte.";
            }
        } else {
            // Première inscription : on enregistre la clé
            $nouvelle_cle_hash = password_hash($cle_fournie, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO config (cle_creation_hash) VALUES (?)");
            $insert->execute([$nouvelle_cle_hash]);
        }

        if (!$error) {
            // Vérifie si le nom d'utilisateur existe déjà
            $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE username = ?");
            $check->execute([$username]);

            if ($check->rowCount() === 0) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (username, password, role) VALUES (?, ?, 'technicien')");
                if ($stmt->execute([$username, $hash])) {
                    ajouter_log("Un nouveau technicien a été créé : $username");
                    $success = true;
                } else {
                    $error = "Erreur lors de la création du compte.";
                }
            } else {
                $error = "Nom d'utilisateur déjà utilisé.";
            }
        }
    } else {
        $error = "Tous les champs sont requis et les mots de passe doivent correspondre.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte technicien</title>
    <link rel="stylesheet" href="csstech/style.css">
</head>
<body>
<div class="container">
    <h2>Créer un compte technicien</h2>

    <?php if ($success): ?>
        <p class="message success">Compte créé avec succès ! <a href="login.php">Se connecter</a></p>
    <?php elseif ($error): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="password" name="confirm" placeholder="Confirmer le mot de passe" required>
        <input type="password" name="cle" placeholder="Clé de création" required>
        <button type="submit">Créer le compte</button>
    </form>

    <p style="margin-top: 15px;"><a href="index.php">← Retour à l'accueil</a></p>
</div>
</body>
</html>
