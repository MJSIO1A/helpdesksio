<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Traitement du clic sur un statut
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'cycle') {
    $id = intval($_GET['id']);

    // Récupération du ticket actuel
    $stmt = $pdo->prepare("SELECT statut FROM tickets WHERE id = ?");
    $stmt->execute([$id]);
    $ticket = $stmt->fetch();

    if ($ticket) {
        $statut_actuel = $ticket['statut'];

        // Déterminer le prochain statut
        $nouveau_statut = match ($statut_actuel) {
            'ouvert' => 'en_cours',
            'en_cours' => 'fermé',
            'fermé' => 'ouvert',
            default => 'ouvert'
        };

        // Mise à jour en BDD
        $update = $pdo->prepare("UPDATE tickets SET statut = ? WHERE id = ?");
        $update->execute([$nouveau_statut, $id]);

        // Log de l'action
        ajouter_log("Technicien {$_SESSION['username']} a changé le statut du ticket #$id de $statut_actuel à $nouveau_statut");

        // Redirection pour éviter le double clic
        header("Location: dashboard.php");
        exit;
    }
}

// Récupération des tickets
$tickets = $pdo->query("SELECT * FROM tickets ORDER BY categorie, date_creation DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Technicien</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .top-bar {
            margin-bottom: 20px;
        }

        .top-bar a {
            margin-right: 15px;
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .statut-ouvert {
            background-color: #e74c3c;
            color: white;
        }

        .statut-en_cours {
            background-color: #f1c40f;
            color: black;
        }

        .statut-fermé {
            background-color: #2ecc71;
            color: white;
        }

        .status-link {
            color: inherit;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 20px;
                background: white;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                padding: 10px;
            }

            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
                border: none;
                border-bottom: 1px solid #ddd;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
                color: #555;
            }

            .top-bar {
                font-size: 0.9em;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .top-bar a {
                margin: 0;
            }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <span>Connecté en tant que <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
    <a href="logout.php">Se déconnecter</a>
    <a href="view_logs.php">Voir les logs</a>
</div>

<h2>Tickets d’assistance</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Catégorie</th>
            <th>Description</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Détail</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td data-label="ID"><?= $ticket['id'] ?></td>
                <td data-label="Nom"><?= htmlspecialchars($ticket['nom_utilisateur']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($ticket['email']) ?></td>
                <td data-label="Catégorie"><?= htmlspecialchars($ticket['categorie']) ?></td>
                <td data-label="Description"><?= htmlspecialchars($ticket['description']) ?></td>
                <td class="statut-<?= $ticket['statut'] ?>" data-label="Statut">
                    <a class="status-link" href="dashboard.php?action=cycle&id=<?= $ticket['id'] ?>">
                        <?= strtoupper($ticket['statut']) ?>
                    </a>
                </td>
                <td data-label="Date"><?= $ticket['date_creation'] ?></td>
                <td data-label="Détail">
                    <a href="ticket.php?id=<?= $ticket['id'] ?>">Voir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
