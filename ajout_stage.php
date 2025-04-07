<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "gestion_stages");

// Vérifie la connexion
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Récupérer la liste des entreprises
$sql = "SELECT id, nom FROM entreprises";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si des entreprises existent
    $entreprises = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $entreprises = [];
}

// Traitement du formulaire après soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $etudiant_id = 1; // L'ID de l'étudiant (tu peux récupérer cela depuis la session ou un autre mécanisme)
    $entreprise_id = $_POST["entreprise_id"];
    
    // Si "Autre" est sélectionné, on ajoute l'entreprise à la base de données
    if ($entreprise_id == "autre") {
        // Récupérer les informations de l'entreprise saisie
        $nom_entreprise = $_POST["nouvelle_entreprise"];
        $adresse_entreprise = $_POST["adresse_entreprise"];
        $email_entreprise = $_POST["email_entreprise"];
        $telephone_entreprise = $_POST["telephone_entreprise"];
        $secteur_entreprise = $_POST["secteur_entreprise"];

        // Insérer la nouvelle entreprise dans la base de données
        $conn->query("INSERT INTO entreprises (nom, adresse, email, telephone, secteur) 
                      VALUES ('$nom_entreprise', '$adresse_entreprise', '$email_entreprise', '$telephone_entreprise', '$secteur_entreprise')");
        $entreprise_id = $conn->insert_id; // On récupère l'ID de l'entreprise nouvellement insérée
    }

    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];
    $description = $_POST["description"];

    // Préparer et exécuter la requête d'insertion dans la base de données
    $stmt = $conn->prepare("INSERT INTO stages (etudiant_id, entreprise_id, date_debut, date_fin, description) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $etudiant_id, $entreprise_id, $date_debut, $date_fin, $description);

    // Exécuter la requête
    if ($stmt->execute()) {
        $message = "Stage ajouté avec succès.";  // Message de succès
    } else {
        $message = "Erreur : " . $stmt->error;  // Message d'erreur
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Stage</title>
    <style>
   * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(to right,rgb(129, 159, 189), #d9e2ec);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.container {
    width: 100%;
    max-width: 800px;
    background:rgb(255, 255, 255);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    padding: 40px;
    transition: 0.3s ease;
}

h2 {
    color: #2c3e50;
    margin-bottom: 30px;
    font-size: 2rem;
    text-align: left;
    margin-left: 10px;
}


form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #34495e;
}

form input,
form textarea,
form select {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.95rem;
    background-color: #f9f9f9;
    transition: 0.2s ease;
}

form input:focus,
form textarea:focus,
form select:focus {
    border-color: #3498db;
    background-color: #fff;
    outline: none;
}

form input[type="submit"] {
    background-color: #3498db;
    color: #fff;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: 0.3s ease;
}

form input[type="submit"]:hover {
    background-color: #2980b9;
}

#other_entreprise {
    background-color: #f6f9fc;
    padding: 20px;
    border: 1px solid #e1e5ea;
    border-radius: 8px;
    margin-top: 15px;
}

textarea {
    resize: vertical;
    min-height: 80px;
}

@media (max-width: 600px) {
    .container {
        padding: 20px;
    }

    h2 {
        font-size: 1.5rem;
    }
}


    </style>
    <script>
        // Afficher l'alerte après l'ajout du stage
        function showAlert(message) {
            alert(message);
        }

        // Afficher le champ "autre entreprise" si l'option "Autre" est sélectionnée
        function toggleOtherEntreprise() {
            var entrepriseSelect = document.getElementById('entreprise_id');
            var otherEntrepriseField = document.getElementById('other_entreprise');
            if (entrepriseSelect.value == 'autre') {
                otherEntrepriseField.style.display = 'block';
            } else {
                otherEntrepriseField.style.display = 'none';
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Ajouter un Stage</h2>
    
    <!-- Formulaire pour ajouter un stage -->
    <form method="POST" action="ajout_stage.php">
        <label for="entreprise_id">Entreprise :</label>
        <select name="entreprise_id" id="entreprise_id" required onchange="toggleOtherEntreprise()">
            <option value="">-- Sélectionner une entreprise --</option>
            <!-- Affichage dynamique des entreprises -->
            <?php foreach ($entreprises as $entreprise): ?>
                <option value="<?= $entreprise['id'] ?>"><?= htmlspecialchars($entreprise['nom']) ?></option>
            <?php endforeach; ?>
            <option value="autre">Autre (saisir le nom)</option>
        </select><br><br>

        <!-- Champ pour entrer une nouvelle entreprise (affiché si "Autre" est sélectionné) -->
         <field>
        <div id="other_entreprise" style="display: none;">
            <label for="nouvelle_entreprise">Nom de l'entreprise :</label>
            <input type="text" name="nouvelle_entreprise" id="nouvelle_entreprise" placeholder="Nom de l'entreprise" /><br><br>

            <label for="adresse_entreprise">Adresse de l'entreprise :</label>
            <textarea name="adresse_entreprise" placeholder="Adresse de l'entreprise"></textarea><br><br>

            <label for="email_entreprise">Email de l'entreprise :</label>
            <input type="email" name="email_entreprise" placeholder="Email de l'entreprise" /><br><br>

            <label for="telephone_entreprise">Téléphone de l'entreprise :</label>
            <input type="text" name="telephone_entreprise" placeholder="Téléphone de l'entreprise" /><br><br>

            <label for="secteur_entreprise">Secteur de l'entreprise :</label>
            <input type="text" name="secteur_entreprise" placeholder="Secteur de l'entreprise" /><br><br>
        </div>
            </field>
            <field >
        <label for="date_debut">Date de début :</label>
        <input type="date" name="date_debut" required><br><br>

        <label for="date_fin">Date de fin :</label>
        <input type="date" name="date_fin" required><br><br>

        <label for="description">Description :</label><br>
        <textarea name="description" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Ajouter le stage">
    </form>
    </field>
            </div>
            
    <?php
    if (isset($message)) {
        echo "<script>showAlert('$message');</script>";  // Affiche l'alerte avec le message
    }
    ?>
</body>
</html>
