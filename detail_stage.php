<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$entreprise_id = $_GET['entreprise_id'];

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=gestion_stages;charset=utf8", "root", "");

// Récupération des infos de l'entreprise
$sql = "SELECT * FROM entreprises WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$entreprise_id]);
$entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupération des stages de cette entreprise
$sql2 = "SELECT * FROM stages WHERE entreprise_id = ?";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([$entreprise_id]);
$stages = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'entreprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* 🎨 Même thème bleu cohérent */

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
        }

        header h1 {
            margin: 0;
            font-size: 1.6em;
        }

        .back-btn {
            background-color: #3b82f6;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #1d4ed8;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #1e3a5f;
            margin-bottom: 15px;
        }

        .entreprise-info p {
            font-size: 1.1em;
            margin: 6px 0;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        th {
            background-color: #e0e7ff;
            color: #1e3a8a;
        }

        td {
            background-color: #f8fafc;
        }

        tr:hover {
            background-color: #e2e8f0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
                color: #475569;
            }

            td {
                border: none;
                border-bottom: 1px solid #e2e8f0;
            }

            tr {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>Détails de l'entreprise</h1>
        <a href="stages.php" class="back-btn">⬅ Retour</a>
    </header>

    <div class="container">
        <h2><?= htmlspecialchars($entreprise['nom']) ?></h2>
        <div class="entreprise-info">
            <p><strong>Secteur :</strong> <?= htmlspecialchars($entreprise['secteur']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($entreprise['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($entreprise['telephone']) ?></p>
        </div>

        <h3>Stages proposés par cette entreprise :</h3>
        <?php if (count($stages) === 0): ?>
            <p>Aucun stage proposé actuellement.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stages as $stage): ?>
                        <tr>
                            <td data-label="Date de début"><?= htmlspecialchars($stage['date_debut']) ?></td>
                            <td data-label="Date de fin"><?= htmlspecialchars($stage['date_fin']) ?></td>
                            <td data-label="Description"><?= htmlspecialchars($stage['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
