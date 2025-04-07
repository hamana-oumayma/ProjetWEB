<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Récupère les informations de l'utilisateur
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
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

    header p {
        margin: 5px 0 0;
        font-size: 1.1em;
    }

    .logout-btn {
        background-color:rgb(241, 95, 59);
        color: white;
        padding: 10px 18px;
        text-decoration: none;
        border: none;
        border-radius: 8px;
        font-size: 1em;
        transition: background-color 0.3s ease;
        cursor: pointer;
    }

    .logout-btn:hover {
        background-color:rgb(162, 172, 199);
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

    .dashboard-content {
        max-width: 1000px;
        margin: 30px auto;
        padding: 25px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    h2, h3 {
        color: #1e3a5f;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #e2e8f0;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #e0e7ff;
        font-weight: bold;
        color: #1e3a8a;
    }

    td {
        background-color: #f8fafc;
    }

    tr:hover {
        background-color: #e2e8f0;
    }

    @media (max-width: 768px) {
        header {
            flex-direction: column;
            text-align: center;
        }

        .logout-btn {
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
        <h1>Bienvenue, <?= htmlspecialchars($user_name) ?> !</h1>
        <!--<p>Votre rôle est : <?= htmlspecialchars($user_role) ?></p>-->
        <a href="logout.php" class="logout-btn">Se déconnecter</a>
    </header>

    <nav>
        <ul>
            <li><a href="stages.php">Voir les stages</a></li>
            <li><a href="inscri.php">Ajouter un stage</a></li>
            <!-- Ajouter plus de liens de navigation selon tes besoins -->
        </ul>
    </nav>

    <section class="dashboard-content">
        <h2>Bienvenue sur votre tableau de bord</h2>
        <p>Gérez vos stages, voyez vos progrès, et bien plus encore.</p>

        <!-- Exemple de tableau pour afficher les stages -->
        <h3>Vos stages en cours :</h3>
        <table>
            <thead>
                <tr>
                    <th>Nom de l'entreprise</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <!-- Ajouter des données dynamiques avec PHP -->
                <?php
                // Connexion à la base de données
                $pdo = new PDO("mysql:host=localhost;dbname=gestion_stages;charset=utf8", "root", "");

                // Récupérer les stages de l'utilisateur connecté
                $sql = "SELECT entreprises.nom AS entreprise, stages.date_debut, stages.date_fin, stages.description
                        FROM stages
                        JOIN entreprises ON stages.entreprise_id = entreprises.id
                        WHERE stages.etudiant_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['user_id']]);
                $stages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($stages) > 0) {
                    foreach ($stages as $stage) {
                        echo "<tr>
                                <td>" . htmlspecialchars($stage['entreprise']) . "</td>
                                <td>" . htmlspecialchars($stage['date_debut']) . "</td>
                                <td>" . htmlspecialchars($stage['date_fin']) . "</td>
                                <td>" . htmlspecialchars($stage['description']) . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucun stage en cours</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>
