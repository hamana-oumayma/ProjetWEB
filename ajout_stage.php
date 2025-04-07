<?php
$conn = new mysqli("localhost", "root", "", "gestion_stages");
if ($conn->connect_error) die("Échec de connexion : " . $conn->connect_error);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST["titre"];
    $entreprise = $_POST["entreprise"];
    $description = $_POST["description"];
    $query = "INSERT INTO stages (titre, entreprise, description) VALUES ('$titre', '$entreprise', '$description')";
    if ($conn->query($query) === TRUE) {
        echo "Stage ajouté avec succès.";
    } else {
        echo "Erreur : " . $conn->error;
    }
}
?>