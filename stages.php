<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=gestion_stages;charset=utf8", "root", "");

// Récupération des entreprises proposant des stages
$sql = "SELECT * FROM entreprises";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres de Stage</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f4f8;
        color: #1e293b;
    }

    header {
        background-color: #1e3a5f;
        color: white;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    header h1 {
        margin: 0;
        font-size: 1.8em;
    }

    .back-btn {
        background-color:rgb(245, 245, 241);
        color: #1e3a5f;
        padding: 10px 18px;
        text-decoration: none;
        border-radius: 8px;
        font-size: 1em;
        transition: background-color 0.3s ease;
    }

    .back-btn:hover {
        background-color:#1e3a5f;
        color:white
    }

    nav {
        background-color: #1e3a5f;
        padding: 10px 0;
        text-align: center;
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    nav ul li {
        display: inline-block;
        margin: 0 20px;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        font-size: 1.05em;
        transition: color 0.3s;
    }

    nav ul li a:hover {
        color: #cbd5e1;
    }

    .stages-list {
        max-width: 1000px;
        margin: 30px auto;
        padding: 25px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    h2 {
        color: #1e3a5f;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid #e2e8f0;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #e0e7ff;
        color: #1e3a8a;
        font-weight: 600;
    }

    td {
        background-color: #f8fafc;
    }

    tr:hover {
        background-color: #e2e8f0;
    }

    .view-btn {
        background-color: #1e3a5f;
        color: white;
        padding: 7px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.95em;
        transition: background-color 0.3s ease;
    }

    .view-btn:hover {
        background-color: #1d4ed8;
    }

    @media (max-width: 768px) {
        header {
            flex-direction: column;
            text-align: center;
        }

        .back-btn {
            margin-top: 10px;
        }

        nav ul li {
            display: block;
            margin: 10px 0;
        }

        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead {
            display: none;
        }

        tr {
            margin-bottom: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        td {
            padding: 10px;
            border: none;
            position: relative;
        }

        td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #475569;
            display: block;
            margin-bottom: 5px;
        }
    }
</style>


</head>
<body>
    <header>
        <h1>Offres de Stage</h1>
        <a href="dashboard.php" class="back-btn">Retour au tableau de bord</a>
    </header>

    <nav>
        <ul>
            <li><a href="stages.php">Voir les stages</a></li>
            <li><a href="inscri.php">Ajouter un stage</a></li>
        </ul>
    </nav>

    <section class="stages-list">
        <h2>Liste des entreprises proposant des stages :</h2>
        
        <!-- Si aucune entreprise n'a de stage disponible -->
        <?php if (count($entreprises) == 0): ?>
            <p>Aucune entreprise n'a actuellement d'offres de stage.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom de l'entreprise</th>
                        <th>Secteur</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Voir les stages</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entreprises as $entreprise): ?>
                        <tr>
                            <td><?= htmlspecialchars($entreprise['nom']) ?></td>
                            <td><?= htmlspecialchars($entreprise['secteur']) ?></td>
                            <td><?= htmlspecialchars($entreprise['email']) ?></td>
                            <td><?= htmlspecialchars($entreprise['telephone']) ?></td>
                            <td><a href="detail_stage.php?entreprise_id=<?= $entreprise['id'] ?>" class="view-btn">Voir les détails</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</body>
</html>
