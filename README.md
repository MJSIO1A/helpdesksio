# Projet - L'Atelier des Jeux

<p align="center">
  <img src="https://github.com/MJSIO1A/helpdesksio/blob/main/images/support.png" width="60%">
</p>

<h2 align="center"><strong>L'Atelier des jeux</strong></h2>

L’Atelier des Jeux est une entreprise spécialisée dans la création de jeux (vidéo, plateau, rôle, cartes), comptant plus de cinquante employés. Intégré au service informatique dans le cadre d’un stage, nous avons pour mission de développer un logiciel d’assistance (Helpdesk) en PHP. Ce système permettra aux utilisateurs de soumettre des tickets en cas de problème, et aux techniciens de les consulter et de les traiter. 

**Le projet comprend un espace pour le client, une interface sécurisée pour les techniciens, la gestion des statuts des tickets, un système de connexion, de logs et un design responsive, le tout avec une mise en page soignée.**

## Les pages du projet


**Une page d'accueil**

**Une page de demande d'assistance**

**Un système de connexion pour technicien**

**Panneau d'accueil avec les tickets**

**Une consultation plus approfondi des tickets**

**La création d'un compte technicien**

**Un système de log (avec une fonctionnalité en plus)** 

**Une option de sécurité(voir dans la partie code)**

**+ du css et responsive(voir dans la partie code)**

_Tout les codes des pages ci-dessous sont après la présentation graphique des pages_

---

**- La page d'accueil :**
![PageAccueil](https://github.com/MJSIO1A/helpdesksio/blob/main/images/accueil.png)

---

**- Page de demande d'assistance :**
![Assistance](https://github.com/MJSIO1A/helpdesksio/blob/main/images/assistance.png)
 
---

**- Page connexion techinicien**
![connexion](https://github.com/MJSIO1A/helpdesksio/blob/main/images/connexion.png)

---

**- Panneau d'accueil :**
![Dashboard](https://github.com/MJSIO1A/helpdesksio/blob/main/images/dashboard.png)

---

**- Consultation plus approfondi du ticket :**
![ApprofTick](https://github.com/MJSIO1A/helpdesksio/blob/main/images/dashboardpro.png)

---

**- Création compte technicien :**
![CreateTech](https://github.com/MJSIO1A/helpdesksio/blob/main/images/inscription.png](https://github.com/MJSIO1A/helpdesksio/blob/main/images/cr%C3%A9ation2.png))

---

**- Système de log (fait en collaboration avec Lucas Sueur + une fonctionnalitée)**
![logs](https://github.com/MJSIO1A/helpdesksio/blob/main/images/logs.png)

## Codes du projet (php)

_- Code Accueil :_ Comme c'est le début, il n'y a pas forcément de php, cela peut être optionnel
```
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Atelier Helpdesk</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur le système d’assistance</h1>
        <p>Vous avez un problème ? Faites une demande d’assistance !</p>

        <div class="buttons">
            <a href="demande.php" class="btn">Faire une demande d’assistance</a>
            <a href="login.php" class="btn">Connexion Technicien</a>
        </div>
    </div>
</body>
</html>
```

---

_- Code de demande assistance :_ Ici nous pouvons voir du php, mais également *require_once 'includes/db.php';*. Cela va nous permettre de nous connecter à la BDD de _helpdesk_, afin ensuite de pourvoir enregistrer les demandes.
```
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
```

---

_- Code connexion technicien :_ De même ici, une connexion à la BDD à partir de la table _utilisateurs_, ici le session_start est très important.
```
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
```

---

_- Code Tableau tickets :_ Un fichier de connexion différent pour une autre connexion à une autre table, la table _Tickets._
```
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
```

---

_- Code Aprofondissement d'un ticket :_
```
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

$ticket_id = intval($_GET['id']); // Récupération de l'ID
$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'], $_POST['statut'])) {
    $description = trim($_POST['description']);
    $statut = $_POST['statut']; // Récupération du nouveau statut

    $allowed_statuses = ['ouvert', 'en_cours', 'fermé'];
    if (in_array($statut, $allowed_statuses)) {
        // UPDATE du ticket
        $stmt = $pdo->prepare("UPDATE tickets SET description = ?, statut = ? WHERE id = ?");
        $stmt->execute([$description, $statut, $ticket_id]);

        // APPEL DE LOG JUSTE APRÈS L'UPDATE
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
```

---

_- Code Création technicien :_ Dans le code, nous pouvons voir un autre fichier php pour la BDD, le mdp et hashé ainsi que la clé.
```
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
```

---

_- Code système de logs + la fonctionnalitée vider les logs avec un bouton :_
```
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
```

---

_- Le css est responsive grâce à media queries, intégré dans le css (fait par Yanis Tanquerel) :_

**Voir dans le dossier du projet pour les css.**


# Codes php connexion à la BDD et le système de log

_- Code connexion à la BDD helpdesk :_
```
<?php
$host = 'localhost';     // l'adresse de MariaDB
$dbname = 'helpdesk';    // le nom de la base
$username = '';      // nom d'utilisateur (mettez l'utilisateur que vous voulez)
$password = '';          // mot de passe (mettez le mdp que vous voulez)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
```

_- Code pour lien vers les log :_
```
<?php
function ajouter_log($message) {
    $date = date('Y-m-d H:i:s');
    $log_message = "[$date] $message" . PHP_EOL;
    file_put_contents('logs/log.txt', $log_message, FILE_APPEND);
}
?>
```

_**Voir le dossier 'Projet Helpdesk' pour le css**_

## Démonstration du projet

![Voir la vidéo](

## Télécharger le projet
