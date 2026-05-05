<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $username=$_POST['username'];
    $password=$_POST['password'];
    if($username==="pesenti" && $password==="verifica")
    $_SESSION['login']=true;
    $_SESSION['username']=$username;
    header("Location: verifica.php");
    exit();
    } else 
    {
        echo "Credenziali errate";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
