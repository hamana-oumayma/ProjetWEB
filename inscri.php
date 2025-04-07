<?php
// Connexion à la base
$pdo = new PDO("mysql:host=localhost;dbname=gestion_stages;charset=utf8", "root", "");

$nom = $_POST['nom'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = 'etudiant'; // ou gestionnaire selon le besoin

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([$nom, $email, $passwordHash, $role]);
    header("Location: login.html?success=1");
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        header("Location: inscri.html?error=email");
    } else {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
