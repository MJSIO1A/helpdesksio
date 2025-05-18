<?php
// demande.php
require_once 'includes/db.php'; // Connexion à la BDD

$success = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $categorie = htmlspecialchars($_POST['categorie']);
    $description = htmlspecialchars($_POST['description']);

    if ($nom && $email && $categorie && $description) {
        $stmt = $pdo->prepare("INSERT INTO tickets (nom_utilisateur, email, categorie, description) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nom, $email, $categorie, $description])) {
            $success = true;
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle demande</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Formulaire de demande d’assistance</h1>

    <?php if ($success): ?>
        <p style="color: green;">Votre demande a bien été enregistrée !</p>
    <?php elseif ($error): ?>
        <p style="color: red;">Erreur : veuillez remplir tous les champs correctement.</p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nom" placeholder="Votre nom" required><br><br>
        <input type="email" name="email" placeholder="Votre email" required><br><br>

        <select name="categorie" required>
            <option value="">-- Choisissez une catégorie --</option>
            <option value="Problème matériel">Problème matériel</option>
            <option value="Bug logiciel">Bug logiciel</option>
            <option value="Connexion réseau">Connexion réseau</option>
            <option value="Autre">Autre</option>
        </select><br><br>

        <textarea name="description" placeholder="Décrivez votre problème..." rows="5" required></textarea><br><br>

        <button type="submit" class="btn">Envoyer la demande</button>
    </form>

    <br>
    <a href="index.php">← Retour à l'accueil</a>
</div>
</body>
</html>
