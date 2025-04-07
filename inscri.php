<?php
// Connexion à la base de données
$host = 'localhost';
$db = 'gestion_stages';
$user = 'root';
$pass = ''; // Change selon ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des données du formulaire
$nom = $_POST['nom'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = 'gestionnaire'; // Par défaut (tu peux le récupérer du formulaire si besoin)

// Hachage du mot de passe
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insertion dans la base
$sql = "INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([$nom, $email, $passwordHash, $role]);
    header("Location: login.html?success=inscription");
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // Doublon email
        header("Location: inscri.html?error=email_utilise");
    } else {
        die("Erreur : " . $e->getMessage());
    }
}
?>
