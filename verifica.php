<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $stmt = $pdo->prepare("SELECT * FROM Utenti WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['login'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Credenziali errate";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    
}
?>

<form method="POST">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Login</button>
</form>