<?php
session_start();

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

// Récupération des données
$email = $_POST['email'];
$password = $_POST['password'];

// Recherche de l'utilisateur
$sql = "SELECT * FROM utilisateurs WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Connexion réussie
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nom'];
    $_SESSION['user_role'] = $user['role'];

    // Redirection vers tableau de bord
    header("Location: dashboard.php");
    exit();
} else {
    // Échec : mauvais identifiants
    header("Location: login.html?error=1");
    exit();
}
?>
