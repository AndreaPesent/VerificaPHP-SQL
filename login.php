<?php
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST") 
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username==="pesenti" && $password==="verifica") 
    {
        $_SESSION['login']=true;
        $_SESSION['username']=$username;
        header("Location: verifica.php");
        exit();
    } else 
    {
        echo "Credenziali non valide";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
    <input type="text" name="username"></br>
    <input type="password" name="password"></br>
    <button type="submit">Login</button>
</form>
</body>
</html>

