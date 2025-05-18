<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php'; // Inclure AVANT d'utiliser ajouter_log()

// Sécurité : vérification de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Sécurité : Vérifier que l'ID existe
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$ticket_id = intval($_GET['id']); // TU récupères ici l'ID
$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'], $_POST['statut'])) {
    $description = trim($_POST['description']);
    $statut = $_POST['statut']; // TU récupères ici le nouveau statut

    $allowed_statuses = ['ouvert', 'en_cours', 'fermé'];
    if (in_array($statut, $allowed_statuses)) {
        // UPDATE du ticket
        $stmt = $pdo->prepare("UPDATE tickets SET description = ?, statut = ? WHERE id = ?");
        $stmt->execute([$description, $statut, $ticket_id]);

        // 🔥 APPEL DE LOG JUSTE APRÈS L'UPDATE
        ajouter_log("Technicien {$_SESSION['username']} a modifié le ticket #{$ticket_id} - Nouveau statut : {$statut}");

        $success = "Ticket mis à jour avec succès.";
    } else {
        $error = "Statut invalide.";
    }
}

// Ensuite récupération du ticket pour affichage
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch();

// Si ticket inexistant
if (!$ticket) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket #<?= htmlspecialchars($ticket['id']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 10px; width: 600px; margin: auto; }
        .field { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        textarea { width: 100%; height: 100px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Ticket #<?= htmlspecialchars($ticket['id']) ?></h1>

    <?php if ($success): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="field">
        <label>Nom :</label>
        <?= htmlspecialchars($ticket['nom_utilisateur']) ?>
    </div>

    <div class="field">
        <label>Email :</label>
        <?= htmlspecialchars($ticket['email']) ?>
    </div>

    <div class="field">
        <label>Catégorie :</label>
        <?= htmlspecialchars($ticket['categorie']) ?>
    </div>

    <form method="POST">
        <div class="field">
            <label>Description :</label>
            <textarea name="description" required><?= htmlspecialchars($ticket['description']) ?></textarea>
        </div>

        <div class="field">
            <label>Statut :</label>
            <select name="statut">
                <option value="ouvert" <?= $ticket['statut'] === 'ouvert' ? 'selected' : '' ?>>Ouvert</option>
                <option value="en_cours" <?= $ticket['statut'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                <option value="fermé" <?= $ticket['statut'] === 'fermé' ? 'selected' : '' ?>>Fermé</option>
            </select>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>

    <br>
    <a href="dashboard.php">← Retour au tableau de bord</a>
</div>

</body>
</html>
