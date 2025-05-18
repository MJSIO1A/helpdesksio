<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/db.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Chemin vers le fichier de log
$log_file = 'logs/log.txt';

// Si l'utilisateur a cliqué sur "Vider les logs"
if (isset($_POST['clear_logs'])) {
    file_put_contents($log_file, ''); // On écrase le fichier avec rien
    header("Location: view_logs.php"); // Rafraîchit la page
    exit;
}

// Lire le fichier de logs
$logs = [];
if (file_exists($log_file)) {
    $logs = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Logs des actions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #3498db;
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
        }

        .actions {
            margin-top: 30px;
            text-align: center;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        button {
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #c0392b;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }

            h1 {
                font-size: 1.5em;
            }

            li {
                font-size: 0.95em;
            }

            button {
                width: 100%;
                font-size: 1em;
            }

            .back-link {
                display: block;
                margin-top: 15px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.3em;
            }

            li {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Historique des actions (Logs)</h1>

    <?php if (!empty($logs)): ?>
        <ul>
            <?php foreach ($logs as $line): ?>
                <li><?= htmlspecialchars($line) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="text-align:center;">Aucun log enregistré pour le moment.</p>
    <?php endif; ?>

    <div class="actions">
        <form method="POST">
            <button type="submit" name="clear_logs">Vider les logs</button>
        </form>
        <a class="back-link" href="dashboard.php">← Retour au tableau de bord</a>
    </div>
</div>

</body>
</html>
